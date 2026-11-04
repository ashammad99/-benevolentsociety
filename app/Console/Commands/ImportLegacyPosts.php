<?php

namespace App\Console\Commands;

use App\Support\SanitizesRichText;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

/**
 * One-time import of the old Laravel 5.5 site's content into the current
 * schema: categories, posts with their cover images, and the contact/about
 * settings.
 *
 * The old dump must already be loaded into a *separate* database
 * (--legacy-db); this command only ever reads from it. The old user accounts
 * are never imported (their password hashes come from a compromised server):
 * everything is credited to --owner, an existing admin.
 *
 * Safe to re-run: existing rows are matched and left alone, settings are only
 * filled while empty. Runs as a dry run unless --commit is given.
 */
class ImportLegacyPosts extends Command
{
    use SanitizesRichText;

    protected $signature = 'society:import-legacy-posts
        {--legacy-db=old_benevole : Database holding the old dump (read-only source)}
        {--images-dir= : Folder holding the files of the old public/photos/main}
        {--images-zip= : Zip containing the old public/photos/main folder}
        {--owner= : Email of the admin credited with the imported content (default: first active admin)}
        {--commit : Write changes; without it nothing is written}';

    protected $description = 'Import categories, posts, cover images and settings from the old site (dry run by default)';

    private const IMAGE_MIMES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    private const MAX_IMAGE_BYTES = 10 * 1024 * 1024;

    /** Old code stored this literal when a post was saved without an image. */
    private const NO_IMAGE_PLACEHOLDER = 'Ibrahim-MH';

    /** Old settings `item` => [new key, label_en, label_ar, group, type]. */
    private const SETTINGS_MAP = [
        'email' => ['email', 'Contact Email', 'البريد الإلكتروني', 'contact', 'email'],
        'phone' => ['phone', 'Contact Phone', 'رقم الهاتف', 'contact', 'phone'],
        'adress' => ['address', 'Office Address', 'عنوان المكتب', 'contact', 'text'],
        'aims' => ['aims', 'Society Aims', 'أهداف الجمعية', 'about', 'richtext'],
        'vision' => ['vision', 'Society Vision', 'رؤية الجمعية', 'about', 'richtext'],
        'msg' => ['about_message', 'About / Vision & Aims Combined', 'عن الجمعية / الرؤية والأهداف', 'about', 'richtext'],
    ];

    /** @var array<int,string> */
    private array $problems = [];

    /** @var array<int,string> */
    private array $notes = [];

    public function handle(): int
    {
        $legacyDb = $this->option('legacy-db');
        $commit = (bool) $this->option('commit');

        if ($legacyDb === config('database.connections.'.config('database.default').'.database')) {
            $this->error('--legacy-db must not be the application database.');

            return self::FAILURE;
        }

        $ownerId = $this->resolveOwner();
        if ($ownerId === null) {
            return self::FAILURE;
        }

        config(['database.connections.legacy' => array_merge(
            config('database.connections.'.config('database.default')),
            ['database' => $legacyDb]
        )]);
        $old = DB::connection('legacy');

        try {
            $old->getPdo();
        } catch (\Throwable $e) {
            $this->error("Cannot connect to legacy database [{$legacyDb}]: ".$e->getMessage());

            return self::FAILURE;
        }

        $images = $this->openImageSource();
        if ($images === false) {
            return self::FAILURE;
        }

        $stats = ['import' => 0, 'already' => 0, 'skipped' => 0, 'no_image' => 0, 'images' => 0,
            'categories_new' => 0, 'categories_existing' => 0, 'settings_fill' => 0, 'settings_kept' => 0];

        $categoryRows = $this->planCategories($old, $ownerId, $stats);
        $categoryIds = array_flip(array_merge(array_column($categoryRows, 'id'), $this->existingCategoryIds));
        $settingRows = $this->planSettings($old, $ownerId, $stats);

        $postRows = [];
        foreach ($old->table('posts')->orderBy('id')->get() as $post) {
            $record = $this->buildRecord($post, $categoryIds, $ownerId);
            if ($record === null) {
                $stats['skipped']++;

                continue;
            }

            $existing = DB::table('posts')->where('id', $post->id)->first();
            if ($existing) {
                if ($existing->title_en === $record['title_en']) {
                    $stats['already']++;
                } else {
                    $this->problems[] = "BLOCKING post #{$post->id}: id already used by a different post ('{$existing->title_en}').";
                    $stats['skipped']++;
                }

                continue;
            }

            $image = $this->resolveImage($post, $images);
            $record['_image'] = $image;
            $stats[$image['bytes'] !== null ? 'images' : 'no_image']++;
            $stats['import']++;
            $postRows[] = $record;
        }

        $this->notes[] = 'All imported content is credited to '.DB::table('users')->where('id', $ownerId)->value('email').
            ' (the old accounts are not imported).';

        $this->report($stats, $commit);
        $this->printList('Problems', $this->problems, 'error');
        $this->printList('Notes', $this->notes, 'warn');

        if (! $commit) {
            $this->newLine();
            $this->info('Dry run — nothing was written. Re-run with --commit to import.');

            return self::SUCCESS;
        }

        if ($this->hasBlockingProblem()) {
            $this->error('Refusing to commit while there are blocking problems (listed above).');

            return self::FAILURE;
        }

        $this->write($categoryRows, $settingRows, $postRows);

        return self::SUCCESS;
    }

    // ── Owner ──────────────────────────────────────────────────────────────

    private function resolveOwner(): ?int
    {
        $query = DB::table('users')->where('role', 'admin')->where('is_active', true);
        $email = $this->option('owner');

        $owner = $email
            ? $query->whereRaw('LOWER(email) = ?', [strtolower($email)])->first()
            : $query->orderBy('id')->first();

        if ($owner === null) {
            $this->error($email
                ? "No active admin with email {$email}."
                : 'No active admin account. Create one with `php artisan society:create-admin` first.');

            return null;
        }

        return (int) $owner->id;
    }

    // ── Categories ─────────────────────────────────────────────────────────

    /** @var array<int,int> ids of old categories that already exist in the new database */
    private array $existingCategoryIds = [];

    /** @return array<int,array<string,mixed>> categories to create */
    private function planCategories($old, int $ownerId, array &$stats): array
    {
        $rows = [];

        foreach ($old->table('categories')->orderBy('id')->get() as $c) {
            $name = $this->unserializeLocalized($c->name);
            if ($name === null || $name['en'] === '' || $name['ar'] === '') {
                $this->problems[] = "BLOCKING category #{$c->id}: name is not a valid en/ar array.";

                continue;
            }

            $existing = DB::table('categories')->where('id', $c->id)->first();
            if ($existing) {
                if ($existing->name_en === $name['en'] && $existing->deleted_at === null) {
                    $this->existingCategoryIds[] = (int) $c->id;
                    $stats['categories_existing']++;
                } else {
                    $this->problems[] = "BLOCKING category #{$c->id} ('{$name['en']}'): id already used by '{$existing->name_en}'".
                        ($existing->deleted_at ? ' (soft-deleted)' : '').'.';
                }

                continue;
            }

            $slug = Str::slug($name['en']);
            $clash = DB::table('categories')
                ->where(fn ($q) => $q->where('slug', $slug)->orWhere('name_en', $name['en'])->orWhere('name_ar', $name['ar']))
                ->value('id');
            if ($clash) {
                $this->problems[] = "BLOCKING category #{$c->id} ('{$name['en']}'): name or slug already used by category #{$clash}.";

                continue;
            }

            $rows[] = [
                'id' => (int) $c->id,
                'name_en' => $name['en'],
                'name_ar' => $name['ar'],
                'slug' => $slug,
                'user_id' => $ownerId,
                'created_at' => $c->created_at ?? now(),
                'updated_at' => $c->updated_at ?? $c->created_at ?? now(),
            ];
            $stats['categories_new']++;
        }

        return $rows;
    }

    // ── Settings ───────────────────────────────────────────────────────────

    /** @return array<string,array<string,mixed>> new key => full row to write */
    private function planSettings($old, int $ownerId, array &$stats): array
    {
        $rows = [];

        foreach ($old->table('settings')->get() as $s) {
            if (! isset(self::SETTINGS_MAP[$s->item])) {
                $this->notes[] = "Old setting '{$s->item}' has no counterpart — not imported.";

                continue;
            }
            [$key, $labelEn, $labelAr, $group, $type] = self::SETTINGS_MAP[$s->item];

            $values = ['value' => null, 'value_en' => null, 'value_ar' => null];
            $localized = $this->unserializeLocalized($s->value);
            if ($localized !== null) {
                $clean = fn ($v) => $type === 'richtext' ? $this->sanitizeRichText($v) : trim(strip_tags($v));
                $values['value_en'] = $localized['en'] === '' ? null : $clean($localized['en']);
                $values['value_ar'] = $localized['ar'] === '' ? null : $clean($localized['ar']);
            } else {
                $values['value'] = trim(strip_tags((string) $s->value)) ?: null;
                if ($key === 'phone' && $values['value'] !== null && ! str_starts_with($values['value'], '+')) {
                    $values['value'] = '+'.$values['value'];
                }
            }

            if (array_filter($values, fn ($v) => $v !== null) === []) {
                continue; // empty in the old site too
            }

            $existing = DB::table('settings')->where('key', $key)->first();
            if ($existing && ($existing->value !== null || $existing->value_en !== null || $existing->value_ar !== null)) {
                $this->notes[] = "Setting '{$key}' already has a value — kept, old value not imported.";
                $stats['settings_kept']++;

                continue;
            }

            $rows[$key] = array_merge([
                'key' => $key,
                'label_en' => $existing->label_en ?? $labelEn,
                'label_ar' => $existing->label_ar ?? $labelAr,
                'group' => $existing->group ?? $group,
                'type' => $existing->type ?? $type,
                'is_public' => $existing->is_public ?? true,
                'updated_by' => $ownerId,
                'created_at' => $existing->created_at ?? now(),
                'updated_at' => now(),
            ], $values);
            $stats['settings_fill']++;

            if ($key === 'phone') {
                $this->notes[] = "Phone will be set to {$values['value']} — check the format in Admin → Settings.";
            }
        }

        return $rows;
    }

    // ── Building one post row ──────────────────────────────────────────────

    private function buildRecord(object $post, array $categoryIds, int $ownerId): ?array
    {
        $title = $this->unserializeLocalized($post->title);
        $content = $this->unserializeLocalized($post->content);

        if ($title === null || $content === null) {
            $this->problems[] = "Post #{$post->id}: title/content is not a valid en/ar array — skipped.";

            return null;
        }

        if (! isset($categoryIds[(int) $post->category])) {
            $this->problems[] = "Post #{$post->id}: category {$post->category} is not available — skipped.";

            return null;
        }

        foreach (['en', 'ar'] as $lang) {
            if (preg_match('/<\s*(script|iframe|object|embed|form)\b|\son\w+\s*=|javascript:/i', $content[$lang] ?? '')) {
                $this->problems[] = "Post #{$post->id}: {$lang} content contains script/iframe/event-handler markup — skipped.";

                return null;
            }
            // Same cleaning the admin applies when a post is saved (PostRequest).
            $content[$lang] = $this->sanitizeRichText($content[$lang]);
        }

        $published = (int) $post->status === 1;
        $now = now()->toDateTimeString();

        if (! $published) {
            $this->notes[] = "Post #{$post->id} is a draft in the old site ('".Str::limit($title['en'], 40)."') — imported as draft.";
        }

        return [
            'id' => (int) $post->id,
            'title_en' => Str::limit(trim($title['en']), 300, ''),
            'title_ar' => Str::limit(trim($title['ar']), 300, ''),
            'excerpt_en' => $this->excerpt($content['en']),
            'excerpt_ar' => $this->excerpt($content['ar']),
            'content_en' => $content['en'],
            'content_ar' => $content['ar'],
            'image_path' => null,
            'category_id' => (int) $post->category,
            'user_id' => $ownerId,
            'status' => $published ? 'published' : 'draft',
            'is_featured' => (int) $post->is_main === 1 ? 1 : 0,
            'published_at' => $published ? ($post->created_at ?? $now) : null,
            'views_count' => 0,
            'created_at' => $post->created_at ?? $now,
            'updated_at' => $post->updated_at ?? $post->created_at ?? $now,
        ];
    }

    /**
     * Old bilingual fields were stored with serialize(['en' => …, 'ar' => …]).
     * allowed_classes=false makes object injection impossible.
     *
     * @return array{en:string,ar:string}|null
     */
    private function unserializeLocalized(?string $raw): ?array
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        $data = @unserialize($raw, ['allowed_classes' => false]);
        if (! is_array($data)) {
            return null;
        }

        return [
            'en' => is_string($data['en'] ?? null) ? $data['en'] : '',
            'ar' => is_string($data['ar'] ?? null) ? $data['ar'] : '',
        ];
    }

    private function excerpt(string $html): ?string
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text));

        return $text === '' ? null : Str::limit($text, 300);
    }

    // ── Images ─────────────────────────────────────────────────────────────

    /**
     * Index the image source as lower-cased file name => reader.
     *
     * @return array<string,callable():string|false>|null|false null = no source given, false = error
     */
    private function openImageSource(): array|null|false
    {
        $dir = $this->option('images-dir');
        $zipPath = $this->option('images-zip');

        if ($dir && $zipPath) {
            $this->error('Give either --images-dir or --images-zip, not both.');

            return false;
        }
        if ($dir) {
            return $this->indexImageDir($dir);
        }
        if ($zipPath) {
            return $this->indexImageZip($zipPath);
        }

        $this->notes[] = 'No --images-dir/--images-zip given: posts will be imported without cover images.';

        return null;
    }

    /** Only regular files directly inside the folder; no recursion, no links. */
    private function indexImageDir(string $dir): array|false
    {
        if (! is_dir($dir)) {
            $this->error("Images folder not found: {$dir}");

            return false;
        }

        $index = [];
        foreach (new \DirectoryIterator($dir) as $file) {
            if ($file->isFile() && ! $file->isLink()) {
                $path = $file->getPathname();
                $index[mb_strtolower($file->getFilename())] = fn () => @file_get_contents($path);
            }
        }

        return $index;
    }

    /**
     * Everything in photos/main/ of the zip.
     * The old archive lacks the UTF-8 flag, so raw names are UTF-8 bytes.
     */
    private function indexImageZip(string $path): array|false
    {
        if (! class_exists(ZipArchive::class)) {
            $this->error('PHP zip extension is not available.');

            return false;
        }

        $zip = new ZipArchive;
        if (! is_file($path) || $zip->open($path, ZipArchive::RDONLY) !== true) {
            $this->error("Cannot open images zip: {$path}");

            return false;
        }

        $index = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i, ZipArchive::FL_ENC_RAW);
            if (! mb_check_encoding($name, 'UTF-8')) {
                $name = mb_convert_encoding($name, 'UTF-8', 'CP437');
            }
            if (preg_match('#(?:^|/)photos/main/([^/]+)$#u', $name, $m)) {
                $index[mb_strtolower($m[1])] = fn () => $zip->getFromIndex($i);
            }
        }

        return $index;
    }

    /** @return array{bytes:?string,ext:?string,source:?string} */
    private function resolveImage(object $post, ?array $index): array
    {
        $none = ['bytes' => null, 'ext' => null, 'source' => null];
        $file = trim((string) $post->image);

        if ($file === '' || $file === self::NO_IMAGE_PLACEHOLDER || $index === null) {
            return $none;
        }

        $key = mb_strtolower($file);
        if (! isset($index[$key])) {
            $stem = mb_strtolower(pathinfo($file, PATHINFO_FILENAME));
            $near = array_filter(array_keys($index), fn ($n) => str_starts_with($n, $stem));
            $hint = $near ? ' Similar file in backup: '.implode(', ', array_slice($near, 0, 3)).'.' : '';
            $this->problems[] = "Post #{$post->id}: cover image '{$file}' not found in backup — imported without image.{$hint}";

            return $none;
        }

        $bytes = $index[$key]();
        $reason = $this->rejectImage($bytes, $mime);
        if ($reason !== null) {
            $this->problems[] = "Post #{$post->id}: cover image '{$file}' rejected ({$reason}) — imported without image.";

            return $none;
        }

        return ['bytes' => $bytes, 'ext' => self::IMAGE_MIMES[$mime], 'source' => $file];
    }

    private function rejectImage(string|false $bytes, ?string &$mime): ?string
    {
        if ($bytes === false || $bytes === '') {
            return 'unreadable';
        }
        if (strlen($bytes) > self::MAX_IMAGE_BYTES) {
            return 'larger than 10 MB';
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($bytes);
        if (! isset(self::IMAGE_MIMES[$mime])) {
            return "unexpected type {$mime}";
        }
        if (@getimagesizefromstring($bytes) === false) {
            return 'not a decodable image';
        }
        // A bare "<?=" occurs by chance inside compressed image data, so it only
        // counts when readable code follows; "<?php" and shell keywords always count.
        if (preg_match('/<\?php|<\?=[\x20-\x7e]{8}|\beval\s*\(|\$_(POST|GET|REQUEST|FILES|COOKIE)\b|base64_decode\s*\(|\b(shell_exec|passthru|proc_open)\s*\(/i', $bytes)) {
            return 'contains PHP code';
        }

        return null;
    }

    // ── Reporting and writing ──────────────────────────────────────────────

    private function hasBlockingProblem(): bool
    {
        foreach ($this->problems as $p) {
            if (str_contains($p, 'BLOCKING')) {
                return true;
            }
        }

        return false;
    }

    private function report(array $s, bool $commit): void
    {
        $this->info($commit ? 'Legacy import (COMMIT)' : 'Legacy import (dry run)');
        $this->table(['Measure', 'Count'], [
            ['Categories to create', $s['categories_new']],
            ['Categories already present', $s['categories_existing']],
            ['Settings to fill (were empty)', $s['settings_fill']],
            ['Settings kept (already set)', $s['settings_kept']],
            ['Posts to import', $s['import']],
            ['  with a valid cover image', $s['images']],
            ['  without a cover image', $s['no_image']],
            ['Posts already imported (same id + title)', $s['already']],
            ['Posts skipped (see problems)', $s['skipped']],
        ]);
    }

    private function printList(string $heading, array $items, string $style): void
    {
        if (! $items) {
            return;
        }

        $this->newLine();
        $this->{$style}("{$heading} (".count($items).'):');
        foreach ($items as $item) {
            $this->line('  - '.$item);
        }
    }

    private function write(array $categories, array $settings, array $posts): void
    {
        $disk = Storage::disk('public');

        DB::transaction(function () use ($categories, $settings, $posts, $disk) {
            foreach ($categories as $row) {
                DB::table('categories')->insert($row);
            }

            foreach ($settings as $key => $row) {
                DB::table('settings')->updateOrInsert(['key' => $key], $row);
            }

            foreach ($posts as $row) {
                $image = $row['_image'];
                unset($row['_image']);

                // One file per post: the admin deletes a post's image file when the
                // post is deleted or its image replaced, so posts that shared a photo
                // in the old site must not share a file here. The hash part lets a
                // re-run after a failed attempt reuse the file.
                if ($image['bytes'] !== null) {
                    $path = 'posts/legacy-'.$row['id'].'-'.substr(sha1($image['bytes']), 0, 12).'.'.$image['ext'];
                    if (! $disk->exists($path)) {
                        $disk->put($path, $image['bytes']);
                    }
                    $row['image_path'] = $path;
                }

                DB::table('posts')->insert($row);
            }
        });

        $this->info(count($categories).' categories, '.count($settings).' settings and '.count($posts).' posts imported.');
    }
}

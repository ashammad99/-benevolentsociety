<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

/**
 * One-time import of the posts (and their cover images) from the old
 * Laravel 5.5 site into the current schema.
 *
 * The old dump must already be loaded into a *separate* database
 * (--legacy-db); this command only ever reads from it. Users, categories and
 * settings are not imported — they are verified to match what already exists.
 *
 * Runs as a dry run unless --commit is given.
 */
class ImportLegacyPosts extends Command
{
    protected $signature = 'society:import-legacy-posts
        {--legacy-db=old_benevole : Database holding the old dump (read-only source)}
        {--images-zip= : Zip containing the old public/photos/main folder}
        {--commit : Write changes; without it nothing is written}';

    protected $description = 'Import posts and cover images from the old site (dry run by default)';

    private const IMAGE_MIMES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    private const MAX_IMAGE_BYTES = 10 * 1024 * 1024;

    /** Old code stored this literal when a post was saved without an image. */
    private const NO_IMAGE_PLACEHOLDER = 'Ibrahim-MH';

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

        $zip = $this->openImagesZip();
        if ($zip === false) {
            return self::FAILURE;
        }
        $imageIndex = $zip ? $this->indexZipImages($zip) : [];

        $this->verifyUsers($old);
        $categories = $this->verifyCategories($old);

        $rows = [];
        $stats = ['import' => 0, 'already' => 0, 'skipped' => 0, 'no_image' => 0, 'images' => 0];

        foreach ($old->table('posts')->orderBy('id')->get() as $post) {
            $record = $this->buildRecord($post, $categories);
            if ($record === null) {
                $stats['skipped']++;

                continue;
            }

            $existing = DB::table('posts')->where('id', $post->id)->first();
            if ($existing) {
                if ($existing->title_en === $record['title_en']) {
                    $stats['already']++;
                } else {
                    $this->problems[] = "Post #{$post->id}: id already used by a different post ('{$existing->title_en}').";
                    $stats['skipped']++;
                }

                continue;
            }

            $image = $this->resolveImage($post, $zip, $imageIndex);
            $record['_image'] = $image;
            if ($image['bytes'] !== null) {
                $stats['images']++;
            } else {
                $stats['no_image']++;
            }

            $stats['import']++;
            $rows[] = $record;
        }

        $this->report($stats, $commit);

        if ($this->problems) {
            $this->newLine();
            $this->error('Problems ('.count($this->problems).'):');
            foreach ($this->problems as $p) {
                $this->line('  - '.$p);
            }
        }
        if ($this->notes) {
            $this->newLine();
            $this->warn('Notes:');
            foreach ($this->notes as $n) {
                $this->line('  - '.$n);
            }
        }

        if (! $commit) {
            $this->newLine();
            $this->info('Dry run — nothing was written. Re-run with --commit to import.');

            return self::SUCCESS;
        }

        if ($this->hasBlockingProblem()) {
            $this->error('Refusing to commit while there are blocking problems (listed above).');

            return self::FAILURE;
        }

        $this->write($rows);

        return self::SUCCESS;
    }

    // ── Verification of data that is expected to exist already ────────────

    private function verifyUsers($old): void
    {
        $new = DB::table('users')->pluck('email', 'id')->map(fn ($e) => strtolower($e));

        foreach ($old->table('users')->get() as $u) {
            $match = $new[$u->id] ?? null;
            if ($match === null || $match !== strtolower($u->email)) {
                $this->problems[] = "BLOCKING user #{$u->id} ({$u->email}) is missing or has a different email in the new database.";
            }
        }
    }

    /** @return array<int,true> ids of categories that match between old and new */
    private function verifyCategories($old): array
    {
        $new = DB::table('categories')->whereNull('deleted_at')->pluck('name_en', 'id');
        $ok = [];

        foreach ($old->table('categories')->get() as $c) {
            $name = $this->unserializeLocalized($c->name)['en'] ?? null;
            if (($new[$c->id] ?? null) === $name) {
                $ok[$c->id] = true;
            } else {
                $this->problems[] = "BLOCKING category #{$c->id} ('{$name}') is missing or differs in the new database.";
            }
        }

        return $ok;
    }

    // ── Building one post row ──────────────────────────────────────────────

    private function buildRecord(object $post, array $categories): ?array
    {
        $title = $this->unserializeLocalized($post->title);
        $content = $this->unserializeLocalized($post->content);

        if ($title === null || $content === null) {
            $this->problems[] = "Post #{$post->id}: title/content is not a valid en/ar array — skipped.";

            return null;
        }

        if (! isset($categories[(int) $post->category])) {
            $this->problems[] = "Post #{$post->id}: category {$post->category} has no matching category — skipped.";

            return null;
        }

        if (! DB::table('users')->where('id', $post->user_id)->exists()) {
            $this->problems[] = "Post #{$post->id}: user {$post->user_id} does not exist — skipped.";

            return null;
        }

        foreach (['en', 'ar'] as $lang) {
            if (preg_match('/<\s*(script|iframe|object|embed|form)\b|\son\w+\s*=|javascript:/i', $content[$lang] ?? '')) {
                $this->problems[] = "Post #{$post->id}: {$lang} content contains script/iframe/event-handler markup — skipped.";

                return null;
            }
        }

        $published = (int) $post->status === 1;
        $now = now()->toDateTimeString();

        if (! $published) {
            $this->notes[] = "Post #{$post->id} is a draft in the old site ('".Str::limit($title['en'], 40)."') — imported as draft.";
        }

        return [
            'id' => (int) $post->id,
            'title_en' => Str::limit($title['en'], 300, ''),
            'title_ar' => Str::limit($title['ar'], 300, ''),
            'excerpt_en' => $this->excerpt($content['en']),
            'excerpt_ar' => $this->excerpt($content['ar']),
            'content_en' => $content['en'],
            'content_ar' => $content['ar'],
            'image_path' => null,
            'category_id' => (int) $post->category,
            'user_id' => (int) $post->user_id,
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

    /** @return ZipArchive|null|false null = no zip given, false = error */
    private function openImagesZip(): ZipArchive|null|false
    {
        $path = $this->option('images-zip');
        if (! $path) {
            $this->notes[] = 'No --images-zip given: posts will be imported without cover images.';

            return null;
        }
        if (! class_exists(ZipArchive::class)) {
            $this->error('PHP zip extension is not available.');

            return false;
        }

        $zip = new ZipArchive;
        if (! is_file($path) || $zip->open($path, ZipArchive::RDONLY) !== true) {
            $this->error("Cannot open images zip: {$path}");

            return false;
        }

        return $zip;
    }

    /**
     * Map lower-cased file name => zip index for everything in photos/main/.
     * The old archive lacks the UTF-8 flag, so raw names are UTF-8 bytes.
     *
     * @return array<string,int>
     */
    private function indexZipImages(ZipArchive $zip): array
    {
        $index = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i, ZipArchive::FL_ENC_RAW);
            if (! mb_check_encoding($name, 'UTF-8')) {
                $name = mb_convert_encoding($name, 'UTF-8', 'CP437');
            }
            if (preg_match('#(?:^|/)photos/main/([^/]+)$#u', $name, $m)) {
                $index[mb_strtolower($m[1])] = $i;
            }
        }

        return $index;
    }

    /** @return array{bytes:?string,ext:?string,source:?string} */
    private function resolveImage(object $post, ?ZipArchive $zip, array $index): array
    {
        $none = ['bytes' => null, 'ext' => null, 'source' => null];
        $file = trim((string) $post->image);

        if ($file === '' || $file === self::NO_IMAGE_PLACEHOLDER) {
            return $none;
        }
        if ($zip === null) {
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

        $bytes = $zip->getFromIndex($index[$key]);
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
            if (str_contains($p, 'BLOCKING') || str_contains($p, 'already used by a different post')) {
                return true;
            }
        }

        return false;
    }

    private function report(array $s, bool $commit): void
    {
        $this->info($commit ? 'Legacy post import (COMMIT)' : 'Legacy post import (dry run)');
        $this->table(['Measure', 'Count'], [
            ['Posts to import', $s['import']],
            ['  with a valid cover image', $s['images']],
            ['  without a cover image', $s['no_image']],
            ['Already imported (same id + title)', $s['already']],
            ['Skipped (see problems)', $s['skipped']],
        ]);
    }

    private function write(array $rows): void
    {
        $disk = Storage::disk('public');

        DB::transaction(function () use ($rows, $disk) {
            foreach ($rows as $row) {
                $image = $row['_image'];
                unset($row['_image']);

                if ($image['bytes'] !== null) {
                    $path = 'posts/legacy-'.substr(sha1($image['bytes']), 0, 16).'.'.$image['ext'];
                    if (! $disk->exists($path)) {
                        $disk->put($path, $image['bytes']);
                    }
                    $row['image_path'] = $path;
                }

                DB::table('posts')->insert($row);
            }
        });

        $this->info(count($rows).' posts imported.');
    }
}

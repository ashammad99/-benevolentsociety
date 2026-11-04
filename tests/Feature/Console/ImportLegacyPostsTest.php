<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportLegacyPostsTest extends TestCase
{
    use RefreshDatabase;

    private string $legacyDb;

    private string $imagesDir;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->legacyDb = tempnam(sys_get_temp_dir(), 'legacy').'.sqlite';
        touch($this->legacyDb);
        config(['database.connections.legacy_fixture' => [
            'driver' => 'sqlite', 'database' => $this->legacyDb, 'prefix' => '', 'foreign_key_constraints' => false,
        ]]);
        $this->buildLegacyDatabase();

        $this->imagesDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'legacy-photos-'.uniqid();
        mkdir($this->imagesDir);
        $this->makeJpeg('shared.jpg');
        $this->makeJpeg('own.JPG');
        // Looks like an image by name, is actually PHP.
        file_put_contents($this->imagesDir.'/evil.jpg', '<?php echo "x"; ?>');
    }

    protected function tearDown(): void
    {
        DB::purge('legacy_fixture');
        DB::purge('legacy');
        @unlink($this->legacyDb);
        array_map('unlink', glob($this->imagesDir.'/*'));
        @rmdir($this->imagesDir);

        parent::tearDown();
    }

    private function buildLegacyDatabase(): void
    {
        $schema = Schema::connection('legacy_fixture');
        $schema->create('categories', function (Blueprint $t) {
            $t->integer('id');
            $t->string('name');
            $t->string('user_id');
            $t->timestamps();
        });
        $schema->create('posts', function (Blueprint $t) {
            $t->integer('id');
            $t->string('title');
            $t->text('content');
            $t->string('image');
            $t->string('category');
            $t->integer('user_id');
            $t->tinyInteger('status');
            $t->tinyInteger('is_main');
            $t->timestamps();
        });
        $schema->create('settings', function (Blueprint $t) {
            $t->integer('id');
            $t->string('item');
            $t->string('desplayName');
            $t->longText('value')->nullable();
            $t->string('type');
            $t->timestamps();
        });

        $l = fn ($en, $ar) => serialize(['en' => $en, 'ar' => $ar]);
        $db = DB::connection('legacy_fixture');

        $db->table('categories')->insert([
            ['id' => 1, 'name' => $l('Health Projects', 'مشاريع صحية'), 'user_id' => '2', 'created_at' => '2019-08-06 04:33:19', 'updated_at' => '2019-08-06 04:33:19'],
            ['id' => 3, 'name' => $l('Projects for Elders', 'مشاريع لكبار السن'), 'user_id' => '2', 'created_at' => '2019-08-06 04:35:18', 'updated_at' => '2019-08-06 04:35:18'],
        ]);

        $post = fn ($id, $image, $category, $status = 1, $content = null) => [
            'id' => $id,
            'title' => $l("Post $id", "منشور $id"),
            'content' => $content ?? $l('<p style="color:red" dir="ltr"><strong>Body</strong></p>', '<p dir="rtl">نص</p>'),
            'image' => $image, 'category' => (string) $category, 'user_id' => 2,
            'status' => $status, 'is_main' => $id % 2, 'created_at' => '2020-01-0'.min($id, 9).' 10:00:00', 'updated_at' => '2020-01-0'.min($id, 9).' 11:00:00',
        ];
        $db->table('posts')->insert([
            $post(2, 'shared.jpg', 1),
            $post(5, 'SHARED.jpg', 3),          // same photo as #2, different case
            $post(6, 'own.jpg', 1, 0),          // draft
            $post(7, 'missing.jpg', 3),
            $post(8, 'evil.jpg', 1),
            $post(9, 'Ibrahim-MH', 3),          // old "no image" placeholder
            $post(10, 'own.jpg', 1, 1, $l('<script>alert(1)</script>', 'x')),
        ]);

        $db->table('settings')->insert([
            ['id' => 1, 'item' => 'email', 'desplayName' => 'email', 'value' => 'info@example.ps', 'type' => '3'],
            ['id' => 2, 'item' => 'phone', 'desplayName' => 'phone', 'value' => '970123456', 'type' => '4'],
            ['id' => 3, 'item' => 'adress', 'desplayName' => 'adress', 'value' => $l('Old Street', 'الشارع القديم'), 'type' => '2'],
            ['id' => 4, 'item' => 'vision', 'desplayName' => 'vision', 'value' => $l('', ''), 'type' => '5'],
            ['id' => 5, 'item' => 'msg', 'desplayName' => 'msg', 'value' => $l('<p onclick="x()">About <b>us</b></p>', 'عنا'), 'type' => '5'],
        ]);
    }

    private function makeJpeg(string $name): void
    {
        $img = imagecreatetruecolor(4, 4);
        imagejpeg($img, $this->imagesDir.'/'.$name);
        imagedestroy($img);
    }

    private function import(array $options = [])
    {
        return $this->artisan('society:import-legacy-posts', array_merge([
            '--legacy-db' => $this->legacyDb,
            '--images-dir' => $this->imagesDir,
        ], $options));
    }

    public function test_dry_run_writes_nothing(): void
    {
        User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->import()->assertSuccessful();

        $this->assertSame(0, DB::table('posts')->count());
        $this->assertSame(0, DB::table('categories')->count());
        $this->assertSame(0, DB::table('settings')->count());
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_requires_an_active_admin(): void
    {
        User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->import(['--commit' => true])->assertFailed();
        $this->assertSame(0, DB::table('posts')->count());
    }

    public function test_commit_imports_categories_posts_images_and_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->import(['--commit' => true])->assertSuccessful();

        // Categories keep their ids and are owned by the admin.
        $this->assertSame([1, 3], DB::table('categories')->orderBy('id')->pluck('id')->all());
        $this->assertDatabaseHas('categories', ['id' => 3, 'name_ar' => 'مشاريع لكبار السن', 'slug' => 'projects-for-elders', 'user_id' => $admin->id]);

        // #10 (script markup) is skipped; everything else is imported with its id.
        $this->assertSame([2, 5, 6, 7, 8, 9], DB::table('posts')->orderBy('id')->pluck('id')->all());
        $this->assertSame(0, DB::table('posts')->where('user_id', '!=', $admin->id)->count());

        $p2 = DB::table('posts')->find(2);
        $this->assertSame('<p><strong>Body</strong></p>', $p2->content_en);   // sanitized like the admin does
        $this->assertSame('published', $p2->status);
        $this->assertSame('2020-01-02 10:00:00', $p2->published_at);
        $this->assertSame('draft', DB::table('posts')->find(6)->status);
        $this->assertNull(DB::table('posts')->find(6)->published_at);

        // Shared photo: each post gets its own file.
        $p5 = DB::table('posts')->find(5);
        $this->assertNotNull($p2->image_path);
        $this->assertNotNull($p5->image_path);
        $this->assertNotSame($p2->image_path, $p5->image_path);
        Storage::disk('public')->assertExists([$p2->image_path, $p5->image_path]);

        // Missing, malicious and placeholder images: post imported without one.
        $this->assertNull(DB::table('posts')->find(7)->image_path);
        $this->assertNull(DB::table('posts')->find(8)->image_path);
        $this->assertNull(DB::table('posts')->find(9)->image_path);
        $this->assertCount(3, Storage::disk('public')->allFiles());

        // Settings: mapped keys, phone gets a +, rich text sanitized, empty ones skipped.
        $this->assertDatabaseHas('settings', ['key' => 'email', 'value' => 'info@example.ps']);
        $this->assertDatabaseHas('settings', ['key' => 'phone', 'value' => '+970123456']);
        $this->assertDatabaseHas('settings', ['key' => 'address', 'value_en' => 'Old Street', 'value_ar' => 'الشارع القديم']);
        $this->assertDatabaseHas('settings', ['key' => 'about_message', 'value_en' => '<p>About <b>us</b></p>']);
        $this->assertDatabaseMissing('settings', ['key' => 'vision']);
    }

    public function test_rerun_is_a_no_op_and_existing_settings_are_kept(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        DB::table('settings')->insert([
            'key' => 'email', 'label_en' => 'Contact Email', 'label_ar' => 'البريد', 'group' => 'contact',
            'type' => 'email', 'value' => 'current@example.org', 'is_public' => true,
        ]);

        $this->import(['--commit' => true])->assertSuccessful();
        $files = Storage::disk('public')->allFiles();

        $this->import(['--commit' => true])->assertSuccessful();

        $this->assertSame(6, DB::table('posts')->count());
        $this->assertSame(2, DB::table('categories')->count());
        $this->assertSame($files, Storage::disk('public')->allFiles());
        $this->assertDatabaseHas('settings', ['key' => 'email', 'value' => 'current@example.org']);
    }

    public function test_refuses_to_commit_when_an_id_is_taken_by_different_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        DB::table('categories')->insert(['id' => 1, 'name_en' => 'Something else', 'name_ar' => 'شيء آخر', 'slug' => 'something-else', 'user_id' => $admin->id]);

        $this->import(['--commit' => true])->assertFailed();

        $this->assertSame(0, DB::table('posts')->count());
        $this->assertSame(1, DB::table('categories')->count());
    }
}

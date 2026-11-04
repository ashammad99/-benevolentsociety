<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function editor(): User
    {
        return User::factory()->create(['role' => 'editor', 'is_active' => true]);
    }

    private function viewer(): User
    {
        return User::factory()->create(['role' => 'viewer', 'is_active' => true]);
    }

    public function test_admin_editor_and_viewer_can_view_media_index(): void
    {
        Media::factory()->count(2)->create();

        foreach ([$this->admin(), $this->editor(), $this->viewer()] as $user) {
            $this->actingAs($user)->get(route('admin.media.index'))->assertOk();
        }
    }

    public function test_editor_can_upload_a_valid_file(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->editor())->post(route('admin.media.upload'), [
            'files' => [UploadedFile::fake()->image('photo.jpg')],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('media', ['name' => 'photo.jpg', 'type' => 'image']);
        $media = Media::where('name', 'photo.jpg')->firstOrFail();
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_viewer_cannot_upload_a_file(): void
    {
        Storage::fake('public');

        $this->actingAs($this->viewer())->post(route('admin.media.upload'), [
            'files' => [UploadedFile::fake()->image('photo.jpg')],
        ])->assertForbidden();

        $this->assertDatabaseCount('media', 0);
    }

    public function test_disallowed_file_type_is_rejected(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.media.upload'), [
            'files' => [UploadedFile::fake()->create('malware.exe', 10)],
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('media', 0);
    }

    public function test_svg_upload_is_rejected(): void
    {
        Storage::fake('public');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>';

        // A real temp file (not UploadedFile::fake(), which reports a MIME
        // type from the name) so the content sniffing runs: a renamed SVG
        // must be rejected too.
        foreach (['logo.svg', 'logo.jpg'] as $name) {
            $path = tempnam(sys_get_temp_dir(), 'svg');
            file_put_contents($path, $svg);

            $this->actingAs($this->admin())->post(route('admin.media.upload'), [
                'files' => [new UploadedFile($path, $name, null, null, true)],
            ])->assertSessionHasErrors();

            @unlink($path);
        }

        $this->assertDatabaseCount('media', 0);
    }

    public function test_oversized_file_is_rejected(): void
    {
        Storage::fake('public');

        // MediaController caps uploads at 50 MB (51200 KB).
        $response = $this->actingAs($this->admin())->post(route('admin.media.upload'), [
            'files' => [UploadedFile::fake()->create('big.pdf', 51201, 'application/pdf')],
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('media', 0);
    }

    public function test_editor_can_update_media_description(): void
    {
        $media = Media::factory()->create();

        $response = $this->actingAs($this->editor())->patch(route('admin.media.update', $media), [
            'description_en' => 'Updated description',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('media', ['id' => $media->id, 'description_en' => 'Updated description']);
    }

    public function test_viewer_cannot_update_media(): void
    {
        $media = Media::factory()->create();

        $this->actingAs($this->viewer())->patch(route('admin.media.update', $media), [
            'description_en' => 'Blocked',
        ])->assertForbidden();
    }

    public function test_admin_can_delete_media_and_its_file(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->image('to-delete.jpg')->store('media', 'public');
        $media = Media::factory()->create(['path' => $path]);

        $response = $this->actingAs($this->admin())->delete(route('admin.media.destroy', $media));

        $response->assertRedirect();
        $this->assertSoftDeleted('media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_viewer_cannot_delete_media(): void
    {
        $media = Media::factory()->create();

        $this->actingAs($this->viewer())
            ->delete(route('admin.media.destroy', $media))
            ->assertForbidden();

        $this->assertDatabaseHas('media', ['id' => $media->id]);
    }

    public function test_admin_can_bulk_delete_media(): void
    {
        Storage::fake('public');
        $items = Media::factory()->count(3)->create();

        $response = $this->actingAs($this->admin())->post(route('admin.media.bulk-delete'), [
            'ids' => $items->pluck('id')->all(),
        ]);

        $response->assertRedirect();
        foreach ($items as $item) {
            $this->assertSoftDeleted('media', ['id' => $item->id]);
        }
    }

    public function test_viewer_cannot_bulk_delete_media(): void
    {
        $items = Media::factory()->count(2)->create();

        $this->actingAs($this->viewer())->post(route('admin.media.bulk-delete'), [
            'ids' => $items->pluck('id')->all(),
        ])->assertForbidden();

        $this->assertDatabaseCount('media', 2);
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
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

    // ── Index ─────────────────────────────────────────────────────────────

    public function test_admin_editor_and_viewer_can_view_posts_index(): void
    {
        Post::factory()->count(3)->create();

        foreach ([$this->admin(), $this->editor(), $this->viewer()] as $user) {
            $this->actingAs($user)
                ->get(route('admin.posts.index'))
                ->assertOk();
        }
    }

    public function test_index_renders_real_post_data(): void
    {
        $post = Post::factory()->create(['title_en' => 'A Very Specific Post Title']);

        $this->actingAs($this->admin())
            ->get(route('admin.posts.index'))
            ->assertOk()
            ->assertSee('A Very Specific Post Title');
    }

    public function test_index_paginates(): void
    {
        Post::factory()->count(20)->create();

        $response = $this->actingAs($this->admin())->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertSee('page=2', false);
    }

    // ── Show ──────────────────────────────────────────────────────────────

    public function test_authorized_user_can_view_a_post(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($this->viewer())
            ->get(route('admin.posts.show', $post))
            ->assertOk()
            ->assertSee($post->title_en);
    }

    // ── Create ────────────────────────────────────────────────────────────

    public function test_create_page_loads_for_admin_and_editor(): void
    {
        foreach ([$this->admin(), $this->editor()] as $user) {
            $this->actingAs($user)
                ->get(route('admin.posts.create'))
                ->assertOk();
        }
    }

    public function test_viewer_cannot_access_create_page(): void
    {
        $this->actingAs($this->viewer())
            ->get(route('admin.posts.create'))
            ->assertForbidden();
    }

    public function test_valid_submission_creates_a_post(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();

        $response = $this->actingAs($this->editor())->post(route('admin.posts.store'), [
            'title_en' => 'New Post Title',
            'title_ar' => 'عنوان المنشور الجديد',
            'excerpt_en' => 'Excerpt',
            'excerpt_ar' => 'مقتطف',
            'content_en' => '<p>Content</p>',
            'content_ar' => '<p>محتوى</p>',
            'category_id' => $category->id,
            'status' => 'draft',
            'is_featured' => '0',
            'image' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $post = Post::where('title_en', 'New Post Title')->firstOrFail();
        $response->assertRedirect(route('admin.posts.show', $post));
        $this->assertDatabaseHas('posts', ['title_en' => 'New Post Title']);
        Storage::disk('public')->assertExists($post->image_path);
    }

    public function test_invalid_submission_returns_validation_errors(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title_en' => '',
        ]);

        $response->assertSessionHasErrors(['title_en', 'title_ar', 'content_en', 'content_ar', 'category_id', 'status']);
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_post_content_is_sanitized_against_stored_xss(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title_en' => 'XSS Test',
            'title_ar' => 'اختبار',
            'content_en' => '<p onclick="alert(1)">Hi</p><script>alert(1)</script>',
            'content_ar' => '<p>مرحبا</p>',
            'category_id' => $category->id,
            'status' => 'draft',
        ]);

        $post = Post::where('title_en', 'XSS Test')->firstOrFail();

        $this->assertStringNotContainsString('<script', $post->content_en);
        $this->assertStringNotContainsString('onclick', $post->content_en);
        $this->assertStringContainsString('<p>Hi</p>', $post->content_en);
    }

    public function test_viewer_cannot_store_a_post(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->viewer())->post(route('admin.posts.store'), [
            'title_en' => 'Blocked',
            'title_ar' => 'محظور',
            'content_en' => 'x',
            'content_ar' => 'x',
            'category_id' => $category->id,
            'status' => 'draft',
        ])->assertForbidden();

        $this->assertDatabaseCount('posts', 0);
    }

    // ── Edit / Update ─────────────────────────────────────────────────────

    public function test_edit_page_loads_prefilled_for_admin_and_editor(): void
    {
        $post = Post::factory()->create(['title_en' => 'Editable Post']);

        foreach ([$this->admin(), $this->editor()] as $user) {
            $this->actingAs($user)
                ->get(route('admin.posts.edit', $post))
                ->assertOk()
                ->assertSee('Editable Post');
        }
    }

    public function test_viewer_cannot_access_edit_page(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($this->viewer())
            ->get(route('admin.posts.edit', $post))
            ->assertForbidden();
    }

    public function test_valid_update_persists_changes(): void
    {
        $post = Post::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin())->put(route('admin.posts.update', $post), [
            'title_en' => 'Updated Title',
            'title_ar' => 'عنوان محدث',
            'content_en' => '<p>Updated</p>',
            'content_ar' => '<p>محدث</p>',
            'category_id' => $category->id,
            'status' => 'draft',
            'is_featured' => '0',
        ]);

        $response->assertRedirect(route('admin.posts.show', $post));
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title_en' => 'Updated Title']);
    }

    public function test_viewer_cannot_update_a_post(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($this->viewer())->put(route('admin.posts.update', $post), [
            'title_en' => 'Blocked Update',
        ])->assertForbidden();

        $this->assertDatabaseMissing('posts', ['title_en' => 'Blocked Update']);
    }

    // ── Delete ────────────────────────────────────────────────────────────

    public function test_admin_and_editor_can_delete_a_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->editor())->delete(route('admin.posts.destroy', $post));

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_viewer_cannot_delete_a_post(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($this->viewer())
            ->delete(route('admin.posts.destroy', $post))
            ->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'deleted_at' => null]);
    }

    // ── Status / featured toggles ────────────────────────────────────────

    public function test_editor_can_toggle_featured(): void
    {
        $post = Post::factory()->create(['is_featured' => false]);

        $response = $this->actingAs($this->editor())->patch(route('admin.posts.toggle-featured', $post));

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'is_featured' => true]);
    }

    public function test_viewer_cannot_toggle_featured(): void
    {
        $post = Post::factory()->create(['is_featured' => false]);

        $this->actingAs($this->viewer())
            ->patch(route('admin.posts.toggle-featured', $post))
            ->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'is_featured' => false]);
    }

    public function test_editor_can_toggle_status_from_draft_to_published(): void
    {
        $post = Post::factory()->create(['status' => 'draft', 'published_at' => null]);

        $response = $this->actingAs($this->editor())->patch(route('admin.posts.toggle-status', $post));

        $response->assertRedirect();
        $post->refresh();
        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
    }

    public function test_viewer_cannot_toggle_status(): void
    {
        $post = Post::factory()->create(['status' => 'draft']);

        $this->actingAs($this->viewer())
            ->patch(route('admin.posts.toggle-status', $post))
            ->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'status' => 'draft']);
    }

    // ── Bulk delete ───────────────────────────────────────────────────────

    public function test_admin_can_bulk_delete_posts(): void
    {
        $posts = Post::factory()->count(3)->create();

        $response = $this->actingAs($this->admin())->post(route('admin.posts.bulk-delete'), [
            'ids' => $posts->pluck('id')->all(),
        ]);

        $response->assertRedirect();
        foreach ($posts as $post) {
            $this->assertSoftDeleted('posts', ['id' => $post->id]);
        }
    }

    public function test_viewer_cannot_bulk_delete_posts(): void
    {
        $posts = Post::factory()->count(2)->create();

        $this->actingAs($this->viewer())->post(route('admin.posts.bulk-delete'), [
            'ids' => $posts->pluck('id')->all(),
        ])->assertForbidden();

        foreach ($posts as $post) {
            $this->assertDatabaseHas('posts', ['id' => $post->id, 'deleted_at' => null]);
        }
    }
}

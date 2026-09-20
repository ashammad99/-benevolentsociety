<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
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

    public function test_admin_editor_and_viewer_can_view_categories_index(): void
    {
        Category::factory()->count(2)->create();

        foreach ([$this->admin(), $this->editor(), $this->viewer()] as $user) {
            $this->actingAs($user)->get(route('admin.categories.index'))->assertOk();
        }
    }

    public function test_viewer_cannot_access_create_page(): void
    {
        $this->actingAs($this->viewer())
            ->get(route('admin.categories.create'))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_category(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.categories.store'), [
            'name_en' => 'Health Projects',
            'name_ar' => 'مشاريع صحية',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name_en' => 'Health Projects', 'slug' => 'health-projects']);
    }

    public function test_viewer_cannot_create_a_category(): void
    {
        $this->actingAs($this->viewer())->post(route('admin.categories.store'), [
            'name_en' => 'Blocked',
            'name_ar' => 'محظور',
        ])->assertForbidden();

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_duplicate_english_name_fails_validation(): void
    {
        Category::factory()->create(['name_en' => 'Existing']);

        $response = $this->actingAs($this->editor())->post(route('admin.categories.store'), [
            'name_en' => 'Existing',
            'name_ar' => 'مختلف',
        ]);

        $response->assertSessionHasErrors('name_en');
    }

    public function test_editor_can_update_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->editor())->put(route('admin.categories.update', $category), [
            'name_en' => 'Updated Name',
            'name_ar' => $category->name_ar,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name_en' => 'Updated Name']);
    }

    public function test_viewer_cannot_update_a_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->viewer())->put(route('admin.categories.update', $category), [
            'name_en' => 'Blocked',
        ])->assertForbidden();
    }

    public function test_admin_can_delete_an_empty_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin())->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_category_with_posts_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        Post::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin())->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHasErrors('category');
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'deleted_at' => null]);
    }

    public function test_viewer_cannot_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->viewer())
            ->delete(route('admin.categories.destroy', $category))
            ->assertForbidden();

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'deleted_at' => null]);
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
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

    // ── Admin-only access (Step 3) ───────────────────────────────────────

    public function test_admin_can_view_users_index(): void
    {
        $this->actingAs($this->admin())->get(route('admin.users.index'))->assertOk();
    }

    public function test_editor_cannot_view_users_index(): void
    {
        $this->actingAs($this->editor())->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_viewer_cannot_view_users_index(): void
    {
        $this->actingAs($this->viewer())->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_editor_cannot_create_a_user(): void
    {
        $this->actingAs($this->editor())->post(route('admin.users.store'), [
            'name' => 'Blocked',
            'email' => 'blocked@example.com',
            'role' => 'viewer',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'blocked@example.com']);
    }

    public function test_admin_can_create_a_user(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.users.store'), [
            'name' => 'New Editor',
            'email' => 'new-editor@example.com',
            'role' => 'editor',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'new-editor@example.com', 'role' => 'editor']);
    }

    // ── UserPolicy (Step 5) ───────────────────────────────────────────────

    public function test_admin_cannot_edit_another_admin(): void
    {
        $actor = $this->admin();
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($actor)
            ->get(route('admin.users.edit', $otherAdmin))
            ->assertForbidden();

        $this->actingAs($actor)
            ->put(route('admin.users.update', $otherAdmin), ['name' => 'Hacked', 'email' => $otherAdmin->email, 'role' => 'admin'])
            ->assertForbidden();
    }

    public function test_admin_can_edit_a_non_admin_user(): void
    {
        $actor = $this->admin();
        $editorUser = User::factory()->create(['role' => 'editor']);

        $response = $this->actingAs($actor)->put(route('admin.users.update', $editorUser), [
            'name' => 'Renamed Editor',
            'email' => $editorUser->email,
            'role' => 'editor',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $editorUser->id, 'name' => 'Renamed Editor']);
    }

    public function test_admin_can_edit_their_own_account(): void
    {
        $actor = $this->admin();

        $response = $this->actingAs($actor)->put(route('admin.users.update', $actor), [
            'name' => 'Renamed Self',
            'email' => $actor->email,
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $actor->id, 'name' => 'Renamed Self']);
    }

    public function test_admin_cannot_demote_themselves(): void
    {
        $actor = $this->admin();

        $response = $this->actingAs($actor)->put(route('admin.users.update', $actor), [
            'name' => $actor->name,
            'email' => $actor->email,
            'role' => 'editor',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseHas('users', ['id' => $actor->id, 'role' => 'admin']);
    }

    public function test_admin_cannot_delete_another_admin(): void
    {
        $actor = $this->admin();
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($actor)
            ->delete(route('admin.users.destroy', $otherAdmin))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        // UserPolicy::delete() denies self-deletion before the controller's
        // own belt-and-suspenders check ever runs, so this 403s at the
        // authorization layer rather than reaching the flash-error branch.
        $actor = $this->admin();

        $response = $this->actingAs($actor)->delete(route('admin.users.destroy', $actor));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $actor->id]);
    }

    public function test_admin_can_delete_a_non_admin_user(): void
    {
        $actor = $this->admin();
        $viewerUser = $this->viewer();

        $response = $this->actingAs($actor)->delete(route('admin.users.destroy', $viewerUser));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $viewerUser->id]);
    }

    public function test_admin_cannot_deactivate_themselves(): void
    {
        $actor = $this->admin();

        $response = $this->actingAs($actor)->patch(route('admin.users.toggle-active', $actor));

        $response->assertSessionHasErrors('user');
        $this->assertDatabaseHas('users', ['id' => $actor->id, 'is_active' => true]);
    }

    public function test_admin_can_deactivate_another_user(): void
    {
        $actor = $this->admin();
        $target = $this->editor();

        $response = $this->actingAs($actor)->patch(route('admin.users.toggle-active', $target));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_active' => false]);
    }
}

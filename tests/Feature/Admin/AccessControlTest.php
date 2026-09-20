<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Step 3 (dashboard access) and Step 4 (auth / active-user middleware)
 * from the Phase 6 brief. Posts/Categories/Media/Ads/Users/Settings
 * per-resource authorization is covered in their own controller tests.
 */
class AccessControlTest extends TestCase
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

    // ── Dashboard access (Step 3: all three roles can reach it) ───────────

    public function test_admin_editor_and_viewer_can_access_dashboard(): void
    {
        foreach ([$this->admin(), $this->editor(), $this->viewer()] as $user) {
            $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();
        }
    }

    // ── Unauthenticated user (Step 4) ──────────────────────────────────────

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_reach_admin_home(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    // ── Inactive user (Step 4) ─────────────────────────────────────────────

    public function test_inactive_user_is_logged_out_and_redirected_with_error(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ── Active verified user (Step 4) ──────────────────────────────────────

    public function test_active_user_can_access_admin_area(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_admin_root_redirects_to_dashboard_for_authenticated_active_user(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin')
            ->assertRedirect(route('admin.dashboard'));
    }
}

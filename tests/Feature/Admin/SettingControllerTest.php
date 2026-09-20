<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingControllerTest extends TestCase
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

    public function test_admin_can_view_settings_index(): void
    {
        $this->actingAs($this->admin())->get(route('admin.settings.index'))->assertOk();
    }

    public function test_editor_cannot_view_settings_index(): void
    {
        $this->actingAs($this->editor())->get(route('admin.settings.index'))->assertForbidden();
    }

    public function test_viewer_cannot_view_settings_index(): void
    {
        $this->actingAs($this->viewer())->get(route('admin.settings.index'))->assertForbidden();
    }

    public function test_admin_can_save_contact_settings(): void
    {
        $response = $this->actingAs($this->admin())->put(route('admin.settings.contact'), [
            'email' => 'contact@example.com',
            'phone' => '+970000000',
            'address_en' => 'Somewhere',
            'address_ar' => 'مكان ما',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $this->assertDatabaseHas('settings', ['key' => 'email', 'value' => 'contact@example.com']);
    }

    public function test_editor_cannot_save_contact_settings(): void
    {
        $this->actingAs($this->editor())->put(route('admin.settings.contact'), [
            'email' => 'blocked@example.com',
            'phone' => '+970000000',
        ])->assertForbidden();

        $this->assertDatabaseMissing('settings', ['key' => 'email']);
    }

    public function test_admin_can_save_social_links_without_seeded_rows(): void
    {
        // Regression test: social keys aren't in the seeder, so saving them
        // for the first time must not fail on the settings.label_en/label_ar
        // NOT NULL columns (see SettingController::upsert()).
        $response = $this->actingAs($this->admin())->put(route('admin.settings.social'), [
            'facebook' => 'https://facebook.com/example',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $this->assertDatabaseHas('settings', ['key' => 'facebook', 'value' => 'https://facebook.com/example']);
    }

    public function test_saving_contact_preserves_existing_richer_label(): void
    {
        Setting::factory()->create([
            'key' => 'email',
            'label_en' => 'Contact Email',
            'label_ar' => 'البريد الإلكتروني',
            'type' => 'email',
            'value' => 'old@example.com',
        ]);

        $this->actingAs($this->admin())->put(route('admin.settings.contact'), [
            'email' => 'new@example.com',
            'phone' => '+970000000',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'email',
            'label_en' => 'Contact Email',
            'value' => 'new@example.com',
        ]);
    }

    public function test_aims_rich_text_is_sanitized_against_stored_xss(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.about'), [
            'vision_en' => 'A safe vision statement.',
            'vision_ar' => 'رؤية آمنة.',
            'aims_en' => '<p onclick="alert(1)">Aim one</p><script>alert(1)</script>',
            'aims_ar' => '<p>هدف واحد</p><img src=x onerror=alert(1)>',
        ]);

        $aims = Setting::where('key', 'aims')->firstOrFail();

        $this->assertStringNotContainsString('<script', $aims->value_en);
        $this->assertStringNotContainsString('onclick', $aims->value_en);
        $this->assertStringContainsString('<p>Aim one</p>', $aims->value_en);

        $this->assertStringNotContainsString('onerror', $aims->value_ar);
        $this->assertStringNotContainsString('<img', $aims->value_ar);
        $this->assertStringContainsString('<p>هدف واحد</p>', $aims->value_ar);
    }
}

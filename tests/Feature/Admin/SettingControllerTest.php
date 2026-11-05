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

        // Address fields deliberately omitted: they are optional.
        $this->actingAs($this->admin())->put(route('admin.settings.contact'), [
            'email' => 'new@example.com',
            'phone' => '+970000000',
        ])->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('settings', [
            'key' => 'email',
            'label_en' => 'Contact Email',
            'value' => 'new@example.com',
        ]);
    }

    public function test_about_text_is_saved_and_shown_on_the_vision_page(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.about'), [
            'about_en' => '<p><strong>Society Vision</strong></p><ul><li>Social justice</li></ul>',
            'about_ar' => '<p><strong>رؤية الجمعية</strong></p>',
        ])->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('settings', ['key' => 'about_message', 'label_en' => 'Vision & Aims']);

        $this->get('/vision')->assertSee('<li>Social justice</li>', false)->assertSee('رؤية الجمعية');
        $this->actingAs($this->admin())->get(route('admin.settings.index'))->assertSee('<li>Social justice</li>', false);
    }

    public function test_about_rich_text_is_sanitized_against_stored_xss(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.about'), [
            'about_en' => '<p onclick="alert(1)">Aim one</p><script>alert(1)</script>',
            'about_ar' => '<p>هدف واحد</p><img src=x onerror=alert(1)>',
        ]);

        $about = Setting::where('key', 'about_message')->firstOrFail();

        $this->assertStringNotContainsString('<script', $about->value_en);
        $this->assertStringNotContainsString('onclick', $about->value_en);
        $this->assertStringContainsString('<p>Aim one</p>', $about->value_en);

        $this->assertStringNotContainsString('onerror', $about->value_ar);
        $this->assertStringNotContainsString('<img', $about->value_ar);
        $this->assertStringContainsString('<p>هدف واحد</p>', $about->value_ar);
    }

    public function test_about_is_sanitized_even_when_the_save_fails_validation(): void
    {
        $admin = $this->admin();

        // A failed save flashes the submitted text as old() input, which the
        // settings page puts back into a {!! !!} editor: it must be sanitized.
        $this->actingAs($admin)->from(route('admin.settings.index'))->put(route('admin.settings.about'), [
            'about_en' => '<p>Aim</p><script>alert(1)</script><img src=x onerror=alert(2)>',
            'about_ar' => ['not', 'a', 'string'],
        ])->assertSessionHasErrors('about_ar');

        $this->assertSame('<p>Aim</p>alert(1)', session()->getOldInput('about_en'));
    }

    public function test_an_emptied_editor_clears_the_about_text(): void
    {
        Setting::factory()->create(['key' => 'about_message', 'type' => 'richtext', 'value' => null, 'value_en' => '<p>Old</p>', 'value_ar' => '<p>قديم</p>']);

        $this->actingAs($this->admin())->put(route('admin.settings.about'), ['about_en' => '<br>', 'about_ar' => '<p> </p>']);

        $about = Setting::where('key', 'about_message')->firstOrFail();
        $this->assertNull($about->value_en);
        $this->assertNull($about->value_ar);
        $this->get('/vision')->assertDontSee('class="about-text"', false);
    }

    public function test_editor_cannot_save_the_about_text(): void
    {
        $this->actingAs($this->editor())->put(route('admin.settings.about'), ['about_en' => '<p>x</p>'])->assertForbidden();
        $this->assertDatabaseMissing('settings', ['key' => 'about_message']);
    }
}

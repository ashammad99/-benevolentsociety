<?php

namespace Tests\Feature\Admin;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdControllerTest extends TestCase
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

    public function test_admin_editor_and_viewer_can_view_ads_index(): void
    {
        Ad::factory()->count(2)->create();

        foreach ([$this->admin(), $this->editor(), $this->viewer()] as $user) {
            $this->actingAs($user)->get(route('admin.ads.index'))->assertOk();
        }
    }

    public function test_viewer_cannot_access_create_page(): void
    {
        $this->actingAs($this->viewer())
            ->get(route('admin.ads.create'))
            ->assertForbidden();
    }

    public function test_editor_can_create_an_ad(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->editor())->post(route('admin.ads.store'), [
            'title_en' => 'Donate Now',
            'title_ar' => 'تبرع الآن',
            'type' => 'banner',
            'status' => 'active',
            'image' => UploadedFile::fake()->image('ad.jpg'),
        ]);

        $response->assertRedirect(route('admin.ads.index'));
        $this->assertDatabaseHas('ads', ['title_en' => 'Donate Now']);
    }

    public function test_viewer_cannot_create_an_ad(): void
    {
        $this->actingAs($this->viewer())->post(route('admin.ads.store'), [
            'title_en' => 'Blocked',
            'title_ar' => 'محظور',
            'type' => 'banner',
            'status' => 'active',
        ])->assertForbidden();

        $this->assertDatabaseCount('ads', 0);
    }

    public function test_invalid_date_range_fails_validation(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.ads.store'), [
            'title_en' => 'Bad Dates',
            'title_ar' => 'تواريخ خاطئة',
            'type' => 'banner',
            'status' => 'active',
            'starts_at' => now()->addWeek()->toDateString(),
            'ends_at' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('ends_at');
    }

    public function test_admin_can_update_an_ad(): void
    {
        $ad = Ad::factory()->create();

        $response = $this->actingAs($this->admin())->put(route('admin.ads.update', $ad), [
            'title_en' => 'Updated Title',
            'title_ar' => $ad->title_ar,
            'type' => $ad->type,
            'status' => $ad->status,
        ]);

        $response->assertRedirect(route('admin.ads.index'));
        $this->assertDatabaseHas('ads', ['id' => $ad->id, 'title_en' => 'Updated Title']);
    }

    public function test_viewer_cannot_update_an_ad(): void
    {
        $ad = Ad::factory()->create();

        $this->actingAs($this->viewer())->put(route('admin.ads.update', $ad), [
            'title_en' => 'Blocked',
        ])->assertForbidden();
    }

    public function test_admin_can_delete_an_ad(): void
    {
        $ad = Ad::factory()->create();

        $response = $this->actingAs($this->admin())->delete(route('admin.ads.destroy', $ad));

        $response->assertRedirect(route('admin.ads.index'));
        $this->assertSoftDeleted('ads', ['id' => $ad->id]);
    }

    public function test_viewer_cannot_delete_an_ad(): void
    {
        $ad = Ad::factory()->create();

        $this->actingAs($this->viewer())
            ->delete(route('admin.ads.destroy', $ad))
            ->assertForbidden();
    }

    public function test_editor_can_toggle_ad_status(): void
    {
        $ad = Ad::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->editor())->patch(route('admin.ads.toggle-status', $ad));

        $response->assertRedirect();
        $this->assertDatabaseHas('ads', ['id' => $ad->id, 'status' => 'paused']);
    }

    public function test_viewer_cannot_toggle_ad_status(): void
    {
        $ad = Ad::factory()->create(['status' => 'active']);

        $this->actingAs($this->viewer())
            ->patch(route('admin.ads.toggle-status', $ad))
            ->assertForbidden();

        $this->assertDatabaseHas('ads', ['id' => $ad->id, 'status' => 'active']);
    }
}

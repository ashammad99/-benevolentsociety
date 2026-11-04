<?php

namespace Tests\Feature\Public;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'society.name' => ['en' => 'Test Charity', 'ar' => 'جمعية الاختبار'],
            'society.short_name' => 'TC',
            'society.logo' => 'images/test-logo.png',
            'society.tagline' => ['en' => 'Test tagline', 'ar' => 'شعار الاختبار'],
            'society.registration' => ['en' => 'Test registration line', 'ar' => 'سطر الترخيص'],
            'society.office_hours' => ['en' => 'Mon to Fri', 'ar' => 'الاثنين إلى الجمعة'],
            'society.fallback.email' => 'hello@test-charity.example',
        ]);
    }

    public function test_public_pages_use_the_configured_identity(): void
    {
        foreach (['/', '/vision', '/contact', '/news', '/programs/rehab'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Test Charity')
                ->assertSee('جمعية الاختبار')
                ->assertSee(asset('images/test-logo.png'), false)
                ->assertSee('Test tagline')
                ->assertSee('Test registration line')
                ->assertDontSee('Benevolent Society')
                ->assertDontSee('جمعية المعاقين');
        }

        $this->get('/contact')->assertSee('Mon to Fri')->assertSee('hello@test-charity.example');
        $this->get('/contact')->assertSee('<title>Contact Us | Test Charity</title>', false);
        $this->get('/?lang=ar')->assertSee('<title>Home | جمعية الاختبار</title>', false);
    }

    public function test_optional_lines_are_hidden_when_null(): void
    {
        config(['society.registration' => null, 'society.office_hours' => null]);

        $this->get('/')->assertOk()->assertDontSee('Test registration line');
        $this->get('/contact')->assertOk()->assertDontSee('Working Hours');
    }

    public function test_login_and_admin_use_the_configured_identity(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Test Charity')
            ->assertSee(asset('images/test-logo.png'), false)
            ->assertDontSee('Benevolent Society');

        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Test Charity')
            ->assertSee('— TC Admin', false)
            ->assertSee(asset('images/test-logo.png'), false);
    }
}

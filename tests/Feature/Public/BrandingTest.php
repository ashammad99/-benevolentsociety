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

    public function test_statistics_are_hidden_when_not_configured(): void
    {
        config(['society.stats' => [], 'society.program_stats' => []]);

        $this->get('/')->assertOk()
            ->assertDontSee('class="stats-bar"', false)
            ->assertDontSee('class="tag"', false);
        $this->get('/login')->assertOk()->assertDontSee('class="panel-stats"', false);
        $this->get('/programs/rehab')->assertOk()->assertDontSee('class="program-stats-row"', false);
    }

    public function test_configured_statistics_are_shown(): void
    {
        $stat = fn ($value, $en, $ar) => ['value' => $value, 'label' => ['en' => $en, 'ar' => $ar]];

        config([
            'society.stats' => [
                $stat('111', 'Alpha', 'ألف'),
                $stat('222', 'Beta', 'باء'),
                $stat('333', 'Gamma', 'جيم'),
                $stat('444', 'Delta', 'دال'),
            ],
            'society.program_stats.rehab' => [
                $stat('555', 'Patients', 'مريض'),
                $stat('666', 'Villages', 'قرية'),
                $stat('777', 'Units', 'وحدة'),
            ],
        ]);

        $this->get('/')->assertSeeInOrder(['111', 'Alpha', '444', 'Delta'])->assertSee('555 Patients');
        // The login panel shows the first three only.
        $this->get('/login')->assertSee('333')->assertDontSee('444');
        $this->get('/programs/rehab')->assertSeeInOrder(['555', '666', '777']);
        $this->get('/programs/education')->assertDontSee('class="program-stats-row"', false);
    }

    public function test_location_comes_from_config(): void
    {
        config([
            'society.city' => ['en' => 'Testville', 'ar' => 'مدينة الاختبار'],
            'society.country' => ['en' => 'Testland', 'ar' => 'دولة الاختبار'],
        ]);

        $this->get('/programs/vocational')->assertOk()
            ->assertSee('traditional crafts of Testland')
            ->assertSee('وحرف تراثية من دولة الاختبار');

        $this->get('/programs/rehab')->assertOk()
            ->assertSee('across Testville, its neighborhoods')
            ->assertSee('في مدينة الاختبار وأحيائها');

        foreach (['/', '/vision', '/contact', '/programs/rehab', '/programs/education', '/programs/vocational', '/programs/social', '/programs/homecare', '/programs/devices'] as $url) {
            $this->get($url)->assertOk()
                ->assertDontSee('Nablus')
                ->assertDontSee('نابلس')
                ->assertDontSee('West Bank')
                // The old hard-coded wording; the country itself now comes from config.
                ->assertDontSee('Palestinian')
                ->assertDontSee('فلسطيني');
        }
    }

    public function test_pages_use_the_organisations_own_photos(): void
    {
        foreach (['/', '/programs/rehab', '/programs/education', '/programs/vocational', '/programs/social', '/programs/homecare', '/programs/devices'] as $url) {
            $this->get($url)->assertOk()
                ->assertDontSee('unsplash.com', false)
                ->assertSee('images/site/', false);
        }

        foreach (glob(public_path('images/site/*.jpg')) as $file) {
            $this->assertNotFalse(@getimagesize($file), basename($file).' is not a readable image');
        }
    }

    public function test_footer_credit_links_to_the_developer_and_can_be_hidden(): void
    {
        config(['society.credit' => ['name' => ['en' => 'Dev Name', 'ar' => 'اسم المطور'], 'url' => 'https://www.linkedin.com/in/example/']]);

        $this->get('/')->assertOk()
            ->assertSee('Developed by')
            ->assertSee('href="https://www.linkedin.com/in/example/" target="_blank" rel="noopener"', false)
            ->assertSee('اسم المطور');

        config(['society.credit' => null]);
        $this->get('/')->assertOk()->assertDontSee('class="footer-credit"', false);
    }
}

<?php

namespace Tests\Feature\Public;

use App\Models\Ad;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdPlacementTest extends TestCase
{
    use RefreshDatabase;

    private function ad(string $type, string $title, array $extra = []): Ad
    {
        return Ad::factory()->live()->create(array_merge(['type' => $type, 'title_en' => $title, 'title_ar' => $title.' AR'], $extra));
    }

    public function test_banner_shows_under_the_menu_on_every_public_page(): void
    {
        $this->ad('banner', 'Banner Ad');

        foreach (['/', '/vision', '/contact', '/news', '/programs/rehab'] as $url) {
            $this->get($url)->assertOk()->assertSee('class="ad-banner"', false)->assertSee('Banner Ad');
        }
    }

    public function test_popup_is_rendered_on_every_page_as_a_hidden_dialog(): void
    {
        $ad = $this->ad('popup', 'Popup Ad', ['url' => 'https://example.org/']);

        $this->get('/')->assertOk()
            ->assertSee('id="ad-popup" data-key="'.$ad->id.'-', false)
            ->assertSee('role="dialog"', false)
            ->assertSee('Popup Ad')
            ->assertSee('href="https://example.org/" class="ad-card-link" target="_blank" rel="noopener"', false);
        $this->get('/vision')->assertSee('id="ad-popup"', false);
    }

    public function test_sidebar_ad_shows_on_the_news_pages_only(): void
    {
        $post = Post::factory()->published()->create();
        $this->ad('sidebar', 'Sidebar Ad');

        $this->get('/news')->assertSee('Sidebar Ad');
        $this->get(route('news.show', $post))->assertSee('Sidebar Ad');
        $this->get('/')->assertDontSee('Sidebar Ad');
        $this->get('/programs/rehab')->assertDontSee('Sidebar Ad');
    }

    public function test_inline_ad_shows_on_the_home_and_program_pages_only(): void
    {
        $this->ad('inline', 'Inline Ad');

        $this->get('/')->assertSee('Inline Ad');
        foreach (['rehab', 'education', 'vocational', 'social', 'homecare', 'devices'] as $slug) {
            $this->get("/programs/$slug")->assertSee('Inline Ad');
        }
        $this->get('/news')->assertDontSee('Inline Ad');
        $this->get('/vision')->assertDontSee('Inline Ad');
    }

    public function test_only_live_ads_show_and_the_newest_per_type_wins(): void
    {
        Ad::factory()->paused()->create(['type' => 'popup', 'title_en' => 'Paused Popup']);
        Ad::factory()->expired()->create(['type' => 'inline', 'title_en' => 'Expired Inline']);
        $this->ad('banner', 'Older Banner', ['created_at' => now()->subDay()]);
        $this->ad('banner', 'Newer Banner');

        $this->get('/')->assertOk()
            ->assertDontSee('Paused Popup')
            ->assertDontSee('Expired Inline')
            ->assertDontSee('id="ad-popup"', false)
            ->assertSee('Newer Banner')
            ->assertDontSee('Older Banner');
    }

    public function test_ads_follow_the_language(): void
    {
        $this->ad('banner', 'English Title', ['title_ar' => 'عنوان عربي']);

        $this->get('/?lang=ar')->assertSee('عنوان عربي')->assertDontSee('English Title');
    }
}

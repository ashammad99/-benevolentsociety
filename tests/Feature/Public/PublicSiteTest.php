<?php

namespace Tests\Feature\Public;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    // ── Basic route status (Step 7) ─────────────────────────────────────────

    public function test_public_routes_return_200(): void
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('vision'))->assertOk();
        $this->get(route('contact'))->assertOk();
        $this->get(route('news.index'))->assertOk();
        $this->get(route('programs.show', 'rehab'))->assertOk();
    }

    public function test_unknown_program_slug_404s(): void
    {
        $this->get(route('programs.show', 'not-a-real-program'))->assertNotFound();
    }

    // ── Unpublished content is not exposed ──────────────────────────────────

    public function test_draft_post_is_not_listed_on_news_index(): void
    {
        $draft = Post::factory()->create(['status' => 'draft', 'title_en' => 'Secret Draft Post']);
        $published = Post::factory()->published()->create(['title_en' => 'Visible Published Post']);

        $response = $this->get(route('news.index'));

        $response->assertOk();
        $response->assertDontSee('Secret Draft Post');
        $response->assertSee('Visible Published Post');
    }

    public function test_draft_post_detail_page_404s(): void
    {
        $draft = Post::factory()->create(['status' => 'draft']);

        $this->get(route('news.show', $draft))->assertNotFound();
    }

    public function test_future_scheduled_post_404s(): void
    {
        $scheduled = Post::factory()->create([
            'status' => 'published',
            'published_at' => now()->addWeek(),
        ]);

        $this->get(route('news.show', $scheduled))->assertNotFound();
    }

    public function test_soft_deleted_post_404s(): void
    {
        $post = Post::factory()->published()->create();
        $post->delete();

        $this->get(route('news.show', $post))->assertNotFound();
    }

    // ── Post detail works ────────────────────────────────────────────────────

    public function test_published_post_detail_renders(): void
    {
        $post = Post::factory()->published()->create([
            'title_en' => 'A Fully Published Post',
            'content_en' => '<p>Real content</p>',
        ]);

        $response = $this->get(route('news.show', $post));

        $response->assertOk();
        $response->assertSee('A Fully Published Post');
        // The organisation is shown as the author, never the admin account that saved the post.
        $response->assertSee(config('society.name.en'));
        $response->assertDontSee($post->user->name);
    }

    public function test_news_pages_are_fully_arabic_in_arabic(): void
    {
        $category = Category::factory()->create(['name_en' => 'Health Projects', 'name_ar' => 'مشاريع صحية']);
        $make = fn ($en, $ar) => Post::factory()->published()->create([
            'category_id' => $category->id,
            'title_en' => $en, 'title_ar' => $ar,
            'excerpt_en' => "$en summary", 'excerpt_ar' => "$ar ملخص",
            'published_at' => '2021-01-26 10:00:00',
        ]);
        $post = $make('Main English Title', 'العنوان الرئيسي');
        $make('Related English Title', 'عنوان ذو صلة');

        $this->get('/?lang=ar')->assertSee('dir="rtl"', false);

        $this->get(route('news.show', $post))->assertOk()
            ->assertSee('مشاريع صحية')
            ->assertSee('26 يناير 2021')
            ->assertSee('عنوان ذو صلة')
            ->assertDontSee('Health Projects')
            ->assertDontSee('Related English Title')
            ->assertDontSee('January');

        $this->get(route('news.index'))->assertOk()->assertSee('مشاريع صحية')->assertDontSee('Health Projects');
        $this->get('/')->assertOk()->assertSee('العنوان الرئيسي')->assertDontSee('Main English Title');

        $this->get(route('news.show', $post).'?lang=en')->assertSee('Health Projects')->assertSee('26 January 2021');
    }

    public function test_viewing_a_post_increments_its_view_count(): void
    {
        $post = Post::factory()->published()->create(['views_count' => 0]);

        $this->get(route('news.show', $post));

        $this->assertSame(1, $post->fresh()->views_count);
    }

    // ── Category filtering ───────────────────────────────────────────────────

    public function test_category_filter_only_shows_matching_published_posts(): void
    {
        $categoryA = Category::factory()->create(['slug' => 'health']);
        $categoryB = Category::factory()->create(['slug' => 'education']);

        Post::factory()->published()->create(['category_id' => $categoryA->id, 'title_en' => 'Health Post']);
        Post::factory()->published()->create(['category_id' => $categoryB->id, 'title_en' => 'Education Post']);

        $response = $this->get(route('news.index', ['category' => 'health']));

        $response->assertOk();
        $response->assertSee('Health Post');
        $response->assertDontSee('Education Post');
    }

    // ── Inactive ads are not exposed ────────────────────────────────────────

    public function test_inactive_ad_is_not_shown_on_home_page(): void
    {
        Ad::factory()->paused()->create(['title_en' => 'Paused Ad Title']);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Paused Ad Title');
    }

    public function test_expired_ad_is_not_shown_on_home_page(): void
    {
        Ad::factory()->expired()->create(['title_en' => 'Expired Ad Title']);

        $response = $this->get(route('home'));

        $response->assertDontSee('Expired Ad Title');
    }

    public function test_live_banner_ad_is_shown_on_home_page(): void
    {
        Ad::factory()->live()->create(['type' => 'banner', 'title_en' => 'Live Banner Ad']);

        $response = $this->get(route('home'));

        $response->assertSee('Live Banner Ad');
    }

    // ── Locale switching (Phase 5) ───────────────────────────────────────────

    public function test_lang_query_param_switches_locale_and_persists_in_session(): void
    {
        $this->get('/?lang=ar')->assertOk();
        $this->assertSame('ar', session('locale'));

        // Subsequent request with no ?lang= should keep using the session value.
        $this->get('/');
        $this->assertSame('ar', app()->getLocale());
    }

    public function test_invalid_lang_value_is_rejected(): void
    {
        $this->get('/?lang=ar');
        $this->assertSame('ar', session('locale'));

        $this->get('/?lang=fr');

        // Invalid value must not overwrite the session or app locale.
        $this->assertNotSame('fr', session('locale'));
        $this->assertNotSame('fr', app()->getLocale());
    }

    public function test_default_locale_is_english_with_no_prior_state(): void
    {
        $this->get('/');

        $this->assertSame('en', app()->getLocale());
    }

    // ── Bilingual content resolution ────────────────────────────────────────

    public function test_category_name_resolves_to_current_locale(): void
    {
        $category = Category::factory()->create(['name_en' => 'English Name', 'name_ar' => 'اسم عربي']);

        app()->setLocale('en');
        $this->assertSame('English Name', $category->name);

        app()->setLocale('ar');
        $this->assertSame('اسم عربي', $category->name);
    }

    public function test_locale_accessor_falls_back_when_translation_missing(): void
    {
        $ad = Ad::factory()->create(['body_en' => 'English body', 'body_ar' => null]);

        app()->setLocale('ar');

        $this->assertSame('English body', $ad->body);
    }
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** The confirm-password and verify-email pages share the login page's branded, bilingual layout. */
class PasswordPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_pages_for_signed_in_users_use_the_branded_layout(): void
    {
        $user = User::factory()->unverified()->create();

        foreach (['/confirm-password', '/verify-email'] as $url) {
            $this->actingAs($user)->get($url)->assertOk()
                ->assertSee('class="login-card"', false)
                ->assertSee(config('society.name.ar'));
        }
    }

    public function test_confirm_password_page_is_arabic_in_arabic(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/confirm-password?lang=ar')->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('أكّد كلمة المرور');
    }
}

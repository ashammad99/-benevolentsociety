<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/** The password pages share the login page's branded, bilingual layout. */
class PasswordPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_password_pages_use_the_branded_layout(): void
    {
        foreach (['/forgot-password', '/reset-password/some-token?email=a%40example.org'] as $url) {
            $this->get($url)->assertOk()
                ->assertSee(config('society.name.en'))
                ->assertSee(asset(config('society.logo')), false)
                ->assertSee('class="login-card"', false);
        }
    }

    public function test_pages_for_signed_in_users_use_the_branded_layout(): void
    {
        $user = User::factory()->unverified()->create();

        foreach (['/confirm-password', '/verify-email'] as $url) {
            $this->actingAs($user)->get($url)->assertOk()
                ->assertSee('class="login-card"', false)
                ->assertSee(config('society.name.ar'));
        }
    }

    public function test_forgot_password_page_and_messages_in_arabic(): void
    {
        $this->get('/forgot-password?lang=ar')->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('نسيت كلمة المرور؟')
            ->assertSee('إرسال رابط إعادة التعيين');

        $this->from('/forgot-password')->post('/forgot-password', ['email' => 'nobody@example.org']);

        $this->get('/forgot-password')->assertSee('لا يوجد مستخدم بهذا البريد الإلكتروني.');
    }

    public function test_reset_password_keeps_the_email_when_switching_language(): void
    {
        $this->get('/reset-password/some-token?email=staff%40example.org')
            ->assertSee('value="staff@example.org"', false)
            ->assertSee('reset-password/some-token?email=staff%40example.org&amp;lang=ar', false);
    }

    public function test_reset_password_validation_errors_are_in_arabic(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->get('/login?lang=ar');
        $this->from("/reset-password/$token")->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-1',
            'password_confirmation' => 'something-else',
        ])->assertSessionHasErrors(['password' => 'تأكيد كلمة المرور غير متطابق.']);
    }
}

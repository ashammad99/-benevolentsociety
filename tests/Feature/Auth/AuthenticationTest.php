<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_failed_login_shows_the_error_and_keeps_the_email(): void
    {
        $user = User::factory()->create();

        $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertRedirect('/login');

        $this->get('/login')
            ->assertSee('These credentials do not match our records.')
            ->assertSee('value="'.$user->email.'"', false);
    }

    public function test_login_error_is_translated_to_arabic(): void
    {
        $user = User::factory()->create();

        $this->get('/login?lang=ar')->assertSee('dir="rtl"', false);

        $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->get('/login')->assertSee('البريد الإلكتروني أو كلمة المرور غير صحيحة.');
    }

    public function test_there_is_no_self_service_password_reset(): void
    {
        // Admins set staff passwords in Admin → Users.
        $this->get('/login')->assertOk()->assertDontSee('Forgot password?');
        $this->get('/forgot-password')->assertNotFound();
        $this->post('/forgot-password', ['email' => 'a@example.org'])->assertNotFound();
        $this->get('/reset-password/some-token')->assertNotFound();
        $this->post('/reset-password', [])->assertNotFound();
    }
}

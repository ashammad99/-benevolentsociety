<?php

namespace Tests\Feature\Console;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_an_active_verified_admin(): void
    {
        $this->artisan('society:create-admin', ['--name' => 'Site Admin', '--email' => 'admin@example.org'])
            ->expectsQuestion('Password (min. 8 characters)', 'secret-pass')
            ->expectsQuestion('Confirm password', 'secret-pass')
            ->assertSuccessful();

        $user = User::where('email', 'admin@example.org')->firstOrFail();
        $this->assertSame('admin', $user->role);
        $this->assertTrue($user->is_active);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check('secret-pass', $user->password));
    }

    public function test_prompts_for_name_and_email_when_not_given(): void
    {
        $this->artisan('society:create-admin')
            ->expectsQuestion('Name', 'Site Admin')
            ->expectsQuestion('Email', 'admin@example.org')
            ->expectsQuestion('Password (min. 8 characters)', 'secret-pass')
            ->expectsQuestion('Confirm password', 'secret-pass')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['email' => 'admin@example.org', 'role' => 'admin']);
    }

    public function test_rejects_mismatched_or_short_passwords_and_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.org']);

        $cases = [
            ['new@example.org', 'secret-pass', 'other-pass'],
            ['new@example.org', 'short', 'short'],
            ['taken@example.org', 'secret-pass', 'secret-pass'],
        ];

        foreach ($cases as [$email, $password, $confirmation]) {
            $this->artisan('society:create-admin', ['--name' => 'X', '--email' => $email])
                ->expectsQuestion('Password (min. 8 characters)', $password)
                ->expectsQuestion('Confirm password', $confirmation)
                ->assertFailed();
        }

        $this->assertDatabaseMissing('users', ['email' => 'new@example.org']);
        $this->assertSame(1, User::where('email', 'taken@example.org')->count());
    }

    public function test_seeder_creates_no_users_and_gives_categories_to_the_first_admin(): void
    {
        $this->seed();
        $this->assertSame(0, User::count());
        $this->assertSame(0, Category::count());
        $settings = Setting::count();
        $this->assertGreaterThan(0, $settings);

        // Seeding again after creating the admin adds the categories and
        // leaves the already-seeded settings alone.
        $admin = User::factory()->create(['role' => 'admin']);
        $this->seed();

        $this->assertSame(1, User::count());
        $this->assertSame($settings, Setting::count());
        $this->assertSame(3, Category::where('user_id', $admin->id)->count());
    }
}

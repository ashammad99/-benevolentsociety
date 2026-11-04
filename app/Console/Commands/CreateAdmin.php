<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Creates an active admin account. There is no public registration, so this
 * is how the first admin is made on a new install; further accounts can then
 * be created from the admin Users screen.
 *
 * The password is always prompted for (never an option) so it doesn't end up
 * in shell history.
 */
class CreateAdmin extends Command
{
    protected $signature = 'society:create-admin
        {--name= : Display name}
        {--email= : Login email address}';

    protected $description = 'Create an active admin account';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Name');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->secret('Password (min. 8 characters)');
        $confirmation = $this->secret('Confirm password');

        // Same rules as the admin Users screen (UserRequest).
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $confirmation,
        ], [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->info("Admin account created for {$user->email}.");

        return self::SUCCESS;
    }
}

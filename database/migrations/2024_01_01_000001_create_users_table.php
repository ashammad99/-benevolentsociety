<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Users table.
 *
 * Improvements over original:
 * - Added `role` enum (admin | editor | viewer) instead of bare `status` int
 * - Kept `status` as boolean `is_active` for clarity
 * - Added `email_verified_at` (Laravel default, good practice)
 * - `remember_token` already standard in Laravel
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Original had `status` int — replaced with clearer columns
            $table->enum('role', ['admin', 'editor', 'viewer'])->default('editor');
            $table->boolean('is_active')->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

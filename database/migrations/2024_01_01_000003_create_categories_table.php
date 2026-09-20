<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Categories table.
 *
 * Improvements over original:
 * - Original stored multilingual data as PHP-serialized strings inside a
 *   single `name` varchar (e.g. `a:2:{s:2:"en";s:15:"Health Projects";...}`).
 *   Replaced with proper `name_en` / `name_ar` columns — clean, queryable,
 *   index-friendly, and works naturally with Laravel's attribute casting.
 * - `user_id` was `varchar(191)` in the original (likely a bug). Fixed to
 *   `foreignId` with a proper FK constraint to `users`.
 * - Added `slug` for SEO-friendly URLs.
 * - Added soft deletes so categories can be deactivated without data loss.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Bilingual names — replaces the serialized `name` column
            $table->string('name_en')->unique();
            $table->string('name_ar')->unique();

            // SEO slug (auto-generate from name_en in the model/seeder)
            $table->string('slug')->unique();

            // Proper FK — original had `user_id varchar(191)` (clearly a bug)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

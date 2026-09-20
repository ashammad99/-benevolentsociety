<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Settings table.
 *
 * The original stored every setting in a flat key-value table. Multilingual
 * values were again PHP-serialized strings. The `type` column was a bare
 * integer with no enum definition in the schema (3 = email, 4 = phone,
 * 5 = richtext, etc., guessed from the seed data).
 *
 * Improvements:
 * - `item` → `key` (conventional name for settings keys).
 * - `desplayName` (typo in original) → `label` for both languages.
 * - `value` stays as a single `text` column for scalar/JSON content, but
 *   for bilingual rich-text settings (`type = richtext`) we add
 *   `value_en` / `value_ar` longText columns instead of serializing.
 * - `type` is now a proper enum: `text`, `email`, `phone`, `url`,
 *   `richtext`, `image`, `json`.
 * - Added `group` so settings can be grouped (general, contact, social…).
 * - Added `is_public` to flag settings safe to expose in API responses.
 * - Unique constraint on `key`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Unique machine-readable key — replaces `item`
            $table->string('key')->unique();

            // Human-readable labels for the admin UI (bilingual)
            $table->string('label_en');
            $table->string('label_ar');

            // Grouping for the admin settings page (e.g. contact, social, seo)
            $table->string('group')->default('general')->index();

            // Data type — replaces the mystery integer `type`
            $table->enum('type', [
                'text',
                'email',
                'phone',
                'url',
                'richtext',   // bilingual HTML stored in value_en / value_ar
                'image',
                'json',
            ])->default('text');

            // Scalar / non-bilingual value (email, phone, url, image path, JSON)
            $table->text('value')->nullable();

            // Bilingual rich-text values (only used when type = richtext)
            // Replaces the PHP-serialized bilingual blobs in the original
            $table->longText('value_en')->nullable();
            $table->longText('value_ar')->nullable();

            // Whether this setting can be read by the public API
            $table->boolean('is_public')->default(false);

            // Settings are system-managed; no user FK needed.
            // But we track who last changed a setting.
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

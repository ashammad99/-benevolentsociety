<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ads / announcements table.
 *
 * The original had `title varchar(500)`, `body text`, `period int`,
 * `status int`. All were monolingual and the purpose of `period` was unclear
 * (number of days to display? a campaign period?).
 *
 * Improvements:
 * - Bilingual `title_en` / `title_ar` and `body_en` / `body_ar`.
 * - `period` int → explicit `starts_at` / `ends_at` timestamps so the app
 *   can query active ads with a simple `where` clause instead of computing
 *   offsets at runtime.
 * - `status` int → enum('active','paused','expired').
 * - Added `type` to distinguish between different ad placements
 *   (banner, popup, sidebar).
 * - Added `url` for click-through links.
 * - Added `image_path` for banner images.
 * - `user_id` proper FK.
 * - Soft deletes.
 * - Index on `status` + `starts_at` + `ends_at` for efficient "live ads" query.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();

            // Bilingual title — replaces `title varchar(500)` (monolingual)
            $table->string('title_en', 500);
            $table->string('title_ar', 500);

            // Bilingual body — replaces `body text` (monolingual)
            $table->text('body_en')->nullable();
            $table->text('body_ar')->nullable();

            // Placement type
            $table->enum('type', ['banner', 'popup', 'sidebar', 'inline'])
                  ->default('banner');

            // Optional image for visual ads
            $table->string('image_path')->nullable();

            // Optional click-through URL
            $table->string('url', 2048)->nullable();

            // Date range — replaces the ambiguous `period int`
            // (original appeared to be "number of days to display")
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();

            // Lifecycle — replaces `status int`
            $table->enum('status', ['active', 'paused', 'expired'])
                  ->default('active')
                  ->index();

            // Creator
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->softDeletes();
            $table->timestamps();

            // Efficient query: "give me all active ads running right now"
            $table->index(['status', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};

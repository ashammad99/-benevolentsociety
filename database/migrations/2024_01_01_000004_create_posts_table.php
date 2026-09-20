<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Posts table.
 *
 * Improvements over original:
 * - Bilingual `title` and `content` were stored as PHP-serialized strings
 *   (same pattern as categories). Replaced with `title_en`, `title_ar`,
 *   `content_en`, `content_ar` — fully queryable and clean.
 * - `category` was a bare `varchar` holding a raw ID string. Replaced with
 *   a proper `foreignId('category_id')` FK to `categories`.
 * - `status` tinyint → proper `enum('draft','published','archived')`.
 * - `is_main` tinyint (used to flag featured/hero posts) → renamed to
 *   `is_featured` boolean for clarity.
 * - `image` kept as string (filesystem path / filename), but moved to
 *   `image_path` to be explicit. Added nullable since drafts may have no image.
 * - Added `excerpt_en` / `excerpt_ar` (nullable) for list-view summaries
 *   without stripping HTML from full content at runtime.
 * - Added `published_at` timestamp to decouple publication date from
 *   `created_at`, enabling scheduled publishing.
 * - Added `views_count` for basic analytics (increment on each page view).
 * - Added soft deletes.
 * - Proper index on `category_id`, `status`, `is_featured`, `published_at`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Bilingual titles — replaces serialized `title varchar(200)`
            $table->string('title_en', 300);
            $table->string('title_ar', 300);

            // Short summaries for cards / list views (optional)
            $table->string('excerpt_en', 500)->nullable();
            $table->string('excerpt_ar', 500)->nullable();

            // Full bilingual content — replaces serialized `content text`
            $table->longText('content_en');
            $table->longText('content_ar');

            // Cover image path relative to storage disk
            $table->string('image_path')->nullable();

            // Proper FK — replaces bare `category varchar(191)` with raw IDs
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Author
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Lifecycle state — replaces ambiguous tinyint `status`
            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('draft')
                  ->index();

            // Featured / hero flag — replaces `is_main tinyint`
            $table->boolean('is_featured')->default(false)->index();

            // Scheduled publishing (null = not yet published)
            $table->timestamp('published_at')->nullable()->index();

            // Basic hit counter
            $table->unsignedBigInteger('views_count')->default(0);

            $table->softDeletes();
            $table->timestamps();

            // Composite index for the most common front-end query:
            // "published posts in category X, newest first"
            $table->index(['category_id', 'status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

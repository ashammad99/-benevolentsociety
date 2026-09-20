<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Media table.
 *
 * The original `media` table stored files without a clear association to any
 * parent entity, making it hard to attach media to posts or other models.
 *
 * Improvements:
 * - Added polymorphic `mediable` columns so this table can serve as a
 *   general media library attachable to any model (posts, settings, ads…).
 * - `type` kept as a string but constrained to an enum of known MIME groups
 *   (`image`, `video`, `document`, `audio`) for validation clarity.
 * - `desc` → `description` (proper column name). Made bilingual (`description_en`
 *   / `description_ar`) to match the rest of the schema, and nullable.
 * - Added `disk` (which filesystem disk: local, s3, etc.), `path`, `mime_type`,
 *   `size_bytes` for a proper file registry.
 * - `status` tinyint → boolean `is_active`.
 * - `user_id` proper FK.
 * - Added `sort_order` to allow manual ordering of attached media.
 * - Soft deletes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // Original `name` — the original filename or a human label
            $table->string('name');

            // Filesystem details
            $table->string('disk')->default('public');   // local | s3 | etc.
            $table->string('path');                       // full path on disk
            $table->string('mime_type')->nullable();      // e.g. image/jpeg
            $table->unsignedBigInteger('size_bytes')->nullable();

            // Media category — replaces bare `type varchar(191)`
            $table->enum('type', ['image', 'video', 'document', 'audio', 'other'])
                  ->default('image');

            // Bilingual description — replaces `desc varchar(400)`
            $table->string('description_en', 500)->nullable();
            $table->string('description_ar', 500)->nullable();

            // Polymorphic association — attach media to any model
            $table->nullableMorphs('mediable'); // adds mediable_id + mediable_type

            // Manual ordering when multiple files are attached to one entity
            $table->unsignedSmallInteger('sort_order')->default(0);

            // Uploader
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Visibility — replaces `status tinyint`
            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['mediable_type', 'mediable_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

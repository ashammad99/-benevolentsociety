<?php

// ════════════════════════════════════════════════════════════════════════════
// Post model — app/Models/Post.php
// ════════════════════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_en', 'title_ar',
        'excerpt_en', 'excerpt_ar',
        'content_en', 'content_ar',
        'image_path',
        'category_id',
        'user_id',
        'status',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
    }

    // ── Accessors ──────────────────────────────────────────────────────────
    /**
     * Current-locale title, falling back to the other language rather than
     * returning blank when the preferred-locale column is empty.
     */
    public function getTitleAttribute(): string
    {
        return App::getLocale() === 'ar'
            ? ($this->title_ar ?: $this->title_en)
            : ($this->title_en ?: $this->title_ar);
    }

    /** Current-locale excerpt, with the same fallback as title. */
    public function getExcerptAttribute(): ?string
    {
        return App::getLocale() === 'ar'
            ? ($this->excerpt_ar ?: $this->excerpt_en)
            : ($this->excerpt_en ?: $this->excerpt_ar);
    }

    /** Current-locale content, with the same fallback as title. */
    public function getContentAttribute(): string
    {
        return App::getLocale() === 'ar'
            ? ($this->content_ar ?: $this->content_en)
            : ($this->content_en ?: $this->content_ar);
    }

    /** Full public URL for the cover image */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? asset('storage/'.$this->image_path)
            : null;
    }

    // ── Scopes ─────────────────────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ── Helpers ────────────────────────────────────────────────────────────
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}

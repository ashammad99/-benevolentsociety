<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Ad extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_en', 'title_ar',
        'body_en', 'body_ar',
        'type', 'image_path', 'url',
        'starts_at', 'ends_at',
        'status', 'user_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ──────────────────────────────────────────────────────────
    /** Falls back to the other language if the current locale's column is empty. */
    public function getTitleAttribute(): string
    {
        return App::getLocale() === 'ar'
            ? ($this->title_ar ?: $this->title_en)
            : ($this->title_en ?: $this->title_ar);
    }

    public function getBodyAttribute(): ?string
    {
        return App::getLocale() === 'ar'
            ? ($this->body_ar ?: $this->body_en)
            : ($this->body_en ?: $this->body_ar);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }

    // ── Scopes ─────────────────────────────────────────────────────────────
    /** Currently live ads (active + within date range, if set) */
    public function scopeLive($query)
    {
        return $query
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }
}

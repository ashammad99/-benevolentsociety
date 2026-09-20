<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

// ════════════════════════════════════════════════════════════════════════════
// Media
// ════════════════════════════════════════════════════════════════════════════
class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'disk', 'path', 'mime_type', 'size_bytes',
        'type', 'description_en', 'description_ar',
        'mediable_id', 'mediable_type',
        'sort_order', 'user_id', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'size_bytes' => 'integer',
        'sort_order' => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ──────────────────────────────────────────────────────────
    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }

    /** Falls back to the other language if the current locale's column is empty. */
    public function getDescriptionAttribute(): ?string
    {
        return App::getLocale() === 'ar'
            ? ($this->description_ar ?: $this->description_en)
            : ($this->description_en ?: $this->description_ar);
    }

    public function getSizeHumanAttribute(): string
    {
        $bytes = $this->size_bytes ?? 0;
        if ($bytes < 1024) {
            return "{$bytes} B";
        }
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }
        if ($bytes < 1073741824) {
            return round($bytes / 1048576, 1).' MB';
        }

        return round($bytes / 1073741824, 2).' GB';
    }

    // ── Scopes ─────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}

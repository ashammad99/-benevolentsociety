<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'label_en', 'label_ar',
        'group', 'type',
        'value', 'value_en', 'value_ar',
        'is_public', 'updated_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ── Accessors ──────────────────────────────────────────────────────────
    /**
     * Returns the resolved value for the current locale.
     * Richtext settings → locale-specific column; others → scalar value.
     */
    public function getResolvedValueAttribute(): ?string
    {
        // Some settings (e.g. `address`) carry bilingual content in
        // value_en/value_ar without being typed 'richtext' — key off
        // whichever bilingual column is actually populated rather than
        // strictly gating on `type`, so those settings resolve correctly.
        if ($this->value_en !== null || $this->value_ar !== null) {
            return App::getLocale() === 'ar'
                ? ($this->value_ar ?: $this->value_en)
                : ($this->value_en ?: $this->value_ar);
        }

        return $this->value;
    }

    // ── Static helpers ─────────────────────────────────────────────────────
    /**
     * Retrieve a setting value by key for the current locale.
     *
     * Usage:
     *   Setting::get('email')           → 'info@example.org'
     *   Setting::get('vision')          → Arabic or English richtext
     *   Setting::get('vision', 'en')    → force English
     */
    public static function get(string $key, ?string $locale = null): ?string
    {
        /** @var self|null $setting */
        $setting = static::where('key', $key)->first();
        if (! $setting) {
            return null;
        }

        if ($setting->value_en !== null || $setting->value_ar !== null) {
            $locale ??= App::getLocale();

            return $locale === 'ar'
                ? ($setting->value_ar ?: $setting->value_en)
                : ($setting->value_en ?: $setting->value_ar);
        }

        return $setting->value;
    }
}

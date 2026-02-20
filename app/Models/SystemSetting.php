<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    public const CACHE_KEY = 'system_settings';

    public const CACHE_TTL = 86400; // 24 hours

    protected $fillable = [
        'favicon',
        'media_id',
        'meta_keywords',
        'meta_author',
        'google_analytics_code',
    ];

    /**
     * Get the media associated with the favicon.
     */
    public function media(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    protected $casts = [
        'meta_keywords' => 'array',
    ];

    /**
     * Get meta keywords as comma-separated string for SEO meta tag.
     */
    public function getMetaKeywordsStringAttribute(): string
    {
        return is_array($this->meta_keywords)
            ? implode(', ', $this->meta_keywords)
            : ($this->meta_keywords ?? '');
    }

    /**
     * Get cached system settings.
     */
    public static function getCached(): ?self
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return static::first();
        });
    }

    /**
     * Clear the system settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Boot the model and add event listeners to clear cache.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }
}

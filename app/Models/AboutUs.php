<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class AboutUs extends Model
{
    use HasUuid, SoftDeletes;

    public const CACHE_KEY = 'about_us';

    public const CACHE_TTL = 86400; // 24 hours

    protected $table = 'about_us';

    protected $fillable = [
        'company_name',
        'description',
        'address',
        'phone',
        'email',
        'whatsapp',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'latitude',
        'longitude',
        'map_embed',
        'map_url',
        'logo',
        'media_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the media associated with the logo.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the first active about us record (non-cached).
     */
    public static function getActive(): ?self
    {
        return static::active()->first();
    }

    /**
     * Get cached about us data.
     */
    public static function getCached(): ?self
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return static::getActive();
        });
    }

    /**
     * Clear the about us cache.
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

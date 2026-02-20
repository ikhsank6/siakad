<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carousel extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'media_id',
        'button_text',
        'button_link',
        'order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the media associated with the carousel image.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

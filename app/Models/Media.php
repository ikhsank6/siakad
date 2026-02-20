<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medias';

    protected $fillable = [
        'uuid',
        'original_filename',
        'filename',
        'size',
        'mime_type',
        'created_by',
        'updated_by',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Media $media) {
            if (!$media->uuid) {
                $media->uuid = (string) Str::uuid();
            }
            if (!$media->created_by && auth()->check()) {
                $media->created_by = auth()->id();
            }
        });

        static::updating(function (Media $media) {
            if (!$media->updated_by && auth()->check()) {
                $media->updated_by = auth()->id();
            }
        });
    }

    /**
     * Get the URL for the media file.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->filename);
    }
}

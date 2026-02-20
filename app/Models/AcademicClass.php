<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicClass extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'grade_level',
        'major',
        'room_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}

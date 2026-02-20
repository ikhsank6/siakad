<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleRule extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'rule_type',
        'value',
        'academic_year_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}

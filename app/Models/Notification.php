<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'from_role_id',
        'to_role_id',
        'message',
        'url',
        'id_reference',
        'read',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function fromRole()
    {
        return $this->belongsTo(Role::class, 'from_role_id');
    }

    public function toRole()
    {
        return $this->belongsTo(Role::class, 'to_role_id');
    }
}

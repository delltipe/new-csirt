<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarningPost extends Model
{
    protected $fillable = [
        'title',
        'content',
        'severity',
        'threat_type',
        'issued_at',
        'affected_systems',
        'recommendations',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'event_type',
        'registration_url',
        'capacity',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];
}

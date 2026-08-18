<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
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
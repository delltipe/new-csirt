<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $table = 'lapor_insiden';

    protected $fillable = [
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'organization',
        'incident_type',
        'description',
        'incident_date',
        'affected_systems',
        'actions_taken',
        'status',
        'severity',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
    ];
}

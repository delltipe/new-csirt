<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $table = 'lapor_insiden';

    protected $fillable = [
        'fullName',
        'email',
        'phoneNumber',
        'foundDate',
        'domain',
        'url',
        'laporDesc',
        'riskType',
        'riskLevel',
        'cvssScore',
        'videoUrl',
        'reference',
        'recommendation',
        'proofPic',
        'status',
    ];

    public $timestamps = true;

    protected $casts = [
        'foundDate' => 'date',
        'cvssScore' => 'float',
    ];
}

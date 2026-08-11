<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LampiranInsiden extends Model
{
    protected $table = 'lampiran_insiden';

    protected $fillable = [
        'laporan_id',
        'jenis', // file | url
        'value',
    ];

    public $timestamps = true;

    public function report(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class, 'laporan_id');
    }
}

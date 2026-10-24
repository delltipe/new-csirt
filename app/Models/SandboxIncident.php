<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SandboxIncident extends Model
{
    /**
     * The database connection name.
     * Explicitly target the dedicated sandbox database.
     */
    protected $connection = 'sandbox';

    /**
     * The table associated with the model.
     */
    protected $table = 'sandbox_incidents';

    public const STATUS_SUBMITTED = 'menunggu_validasi';

    protected $fillable = [
        'tiket_no',
        'kategori_insiden',
        'waktu_kejadian',
        'lokasi_url',
        'down_time',
        'deskripsi',
        'tindakan_teknis',
        'bukti_urls',
        'status',
    ];

    public $timestamps = true;

    protected $casts = [
        'waktu_kejadian' => 'datetime',
        'bukti_urls' => 'array',
    ];

    /**
     * Generate a unique, clearly fictional demo ticket reference (DEMO-YYYY-XXXXXXXX).
     */
    public static function generateTiketNo(): string
    {
        $year = now()->year;

        do {
            $randomString = Str::upper(Str::random(8));
            $tiketNo = "DEMO-{$year}-{$randomString}";
        } while (static::where('tiket_no', $tiketNo)->exists());

        return $tiketNo;
    }
}

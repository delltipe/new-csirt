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

    public const STATUS_PENDING = 'menunggu_validasi';
    public const STATUS_VALIDATED = 'divalidasi';
    public const STATUS_IN_PROGRESS = 'ditindaklanjuti';
    public const STATUS_RECOVERED = 'dipulihkan';
    public const STATUS_DONE = 'selesai';
    public const STATUS_REJECTED = 'ditolak';

    public const STATUS_SUBMITTED = self::STATUS_PENDING;

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

    public static function labels(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Validasi',
            self::STATUS_VALIDATED => 'Divalidasi',
            self::STATUS_IN_PROGRESS => 'Ditindaklanjuti',
            self::STATUS_RECOVERED => 'Dipulihkan',
            self::STATUS_DONE => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public static function transitions(): array
    {
        return [
            self::STATUS_PENDING => [self::STATUS_VALIDATED, self::STATUS_REJECTED],
            self::STATUS_VALIDATED => [self::STATUS_IN_PROGRESS, self::STATUS_REJECTED],
            self::STATUS_IN_PROGRESS => [self::STATUS_RECOVERED, self::STATUS_REJECTED],
            self::STATUS_RECOVERED => [self::STATUS_DONE],
            self::STATUS_DONE => [],
            self::STATUS_REJECTED => [],
        ];
    }

    public function statusLabel(): string
    {
        return self::labels()[$this->status] ?? $this->status;
    }

    public function canTransitionTo(string $next): bool
    {
        return in_array($next, self::transitions()[$this->status] ?? [], true);
    }

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

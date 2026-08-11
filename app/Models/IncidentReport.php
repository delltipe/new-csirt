<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncidentReport extends Model
{
    protected $table = 'lapor_insiden';

    public const STATUS_PENDING = 'menunggu_validasi';
    public const STATUS_VALIDATED = 'divalidasi';
    public const STATUS_IN_PROGRESS = 'ditindaklanjuti';
    public const STATUS_RECOVERED = 'dipulihkan';
    public const STATUS_DONE = 'selesai';
    public const STATUS_REJECTED = 'ditolak';

    protected $fillable = [
        'user_id',
        'tiket_no',
        'kategori_insiden',
        'waktu_kejadian',
        'lokasi_url',
        'down_time',
        'deskripsi',
        'tindakan_teknis',
        'cwe',
        'severity',
        'status',
    ];

    public $timestamps = true;

    protected $casts = [
        'waktu_kejadian' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(LampiranInsiden::class, 'laporan_id');
    }

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
}

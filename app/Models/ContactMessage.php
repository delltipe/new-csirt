<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_us';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSED = 'diproses';
    public const STATUS_DONE = 'selesai';
    public const STATUS_REJECTED = 'ditolak';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization',
        'subject',
        'message',
        'inquiry_type',
        'status',
        'admin_note',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    public static function labels(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSED => 'Diproses',
            self::STATUS_DONE => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public static function transitions(): array
    {
        return [
            self::STATUS_PENDING => [self::STATUS_PROCESSED, self::STATUS_REJECTED],
            self::STATUS_PROCESSED => [self::STATUS_DONE, self::STATUS_REJECTED],
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TacAgreement extends Model
{
    protected $table = 'tac_agreements';

    protected $fillable = [
        'user_id',
        'version',
        'agreed_at',
    ];

    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_us';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization',
        'subject',
        'message',
        'inquiry_type',
        'status',
    ];
}

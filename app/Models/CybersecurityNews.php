<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CybersecurityNews extends Model
{
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'image_url',
        'source_url',
        'author',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}

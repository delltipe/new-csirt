<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CybersecurityGuide extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'difficulty_level',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CybersecurityNews extends Model
{
    protected $table = 'berita_siber';
    
    // Disable timestamps because they aren't in your migration
    public $timestamps = false; 

    protected $fillable = [
        'title',
        'description', // Matches migration
        'thumbnail',   // Matches migration
        'source',      // Matches migration
        'date',        // Matches migration
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarningPost extends Model
{
    protected $table = 'peringatan_keamanan';
    
    // Disable timestamps since they aren't in your migration
    public $timestamps = false; 

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'source',
        'date',
        'file_path', // For uploaded image file
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
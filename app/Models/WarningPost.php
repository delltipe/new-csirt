<?php

namespace App\Models;

use App\Models\Concerns\HasOptimizedImages;
use Illuminate\Database\Eloquent\Model;

class WarningPost extends Model
{
    use HasOptimizedImages;

    protected $table = 'peringatan_keamanan';
    
    // Disable timestamps since they aren't in your migration
    public $timestamps = true; 

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
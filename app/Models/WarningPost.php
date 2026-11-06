<?php

namespace App\Models;

use App\Models\Concerns\HasOptimizedImages;
use Illuminate\Database\Eloquent\Model;

class WarningPost extends Model
{
    use HasOptimizedImages;

    protected $table = 'peringatan_keamanan';
    
    // The content table includes created_at and updated_at.
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

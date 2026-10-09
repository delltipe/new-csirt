<?php

namespace App\Models;

use App\Models\Concerns\HasOptimizedImages;
use Illuminate\Database\Eloquent\Model;

class Infographic extends Model
{
    use HasOptimizedImages;

    protected $table = 'infografis_keamanan';
    
    public $timestamps = true;
    
    protected $fillable = [
        'title',
        'thumbnail',
    ];
}

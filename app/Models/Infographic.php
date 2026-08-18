<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infographic extends Model
{
    protected $table = 'infografis_keamanan';
    
    public $timestamps = true;
    
    protected $fillable = [
        'title',
        'thumbnail',
    ];
}

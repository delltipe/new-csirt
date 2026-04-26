<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawRulePost extends Model
{
    protected $table = 'peraturan_kebijakan';
    
    public $timestamps = false;
    
    protected $fillable = [
        'title',
        'description',
        'link',
        'date',
        'time',
        'downloadAmount',
        'file_path', // For uploaded document
    ];

    protected $casts = [
        'date' => 'date',
    ];
}

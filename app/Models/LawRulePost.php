<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawRulePost extends Model
{
    protected $table = 'peraturan_kebijakan';
    
    protected $fillable = [
        'title',
        'content',
        'summary',
        'law_number',
        'regulation_type',
        'effective_date',
        'document_url',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];
}

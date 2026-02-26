<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infographic extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'category',
        'tags',
    ];
}

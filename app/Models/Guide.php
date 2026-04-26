<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    protected $table = 'panduan_teknis';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'author',
        'link',
        'file_path',
    ];
}

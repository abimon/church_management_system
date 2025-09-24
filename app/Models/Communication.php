<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $fillable =[
        'poster',
        'title',
        'description',
        'category',
        'created_by'
    ];
}

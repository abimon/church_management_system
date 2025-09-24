<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable =[
        'title',
        'content',
        'recepient_id',
        'datetime',
        'created_by'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retention extends Model
{
    protected $fillable =[
        'type',
        'name',
        'contact',
        'description',
        'status',
        'assigned_to'
    ];
}

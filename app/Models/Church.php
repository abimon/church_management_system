<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    protected $fillable =[
        'name',
        'location',
        'address',
        'contact',
        'password',
        'email',
        'is_approved'
    ];
}

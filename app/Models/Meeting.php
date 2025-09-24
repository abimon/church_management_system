<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable =[
        'title',
        'date',
        'start_time',
        'end_time',
        'location',
        'agenda',
        'action_points',
        'user_id'
    ];
}

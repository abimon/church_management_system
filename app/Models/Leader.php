<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leader extends Model
{
    protected $fillable =[
        'user_id',
        'role',
        'status',
        'is_bm'
    ];
}

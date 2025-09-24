<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable =[
        'name',
        'is_active',
        'target',
        'parent_account_id',
    ];
}

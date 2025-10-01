<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable =[
        'account_id',
        'amount',
        'status',
        'payment_method',
        'reference',
        'logged_by',
        'user_id',
    ];
}

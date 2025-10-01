<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable =[
        'account_id',
        'amount',
        'description',
        'received_by',
        'logged_by',
    ];
}

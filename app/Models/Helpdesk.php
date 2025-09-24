<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Helpdesk extends Model
{
    protected $fillable = [
        'name', 'contact', 'subject', 'message', 'status'
    ];
}

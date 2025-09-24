<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $fillable = [
        'subject',
        'content',
        'whatsapp',
        'email',
        'push_notification',
        'sms',
        'created_by'
    ];
}

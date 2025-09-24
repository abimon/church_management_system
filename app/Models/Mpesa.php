<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mpesa extends Model
{
    protected $fillable = [
        "TransactionType",
        "account_id",
        "TransAmount",
        "MpesaReceiptNumber",
        "TransactionDate",
        "PhoneNumber",
        "response",
    ];
}

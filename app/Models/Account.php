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
    public function parent_account(){
        return $this->belongsTo(Account::class,'parent_account_id');
    }
    public function payments(){
        return $this->hasMany(Payment::class,'account_id','id');
    }
    public function expenses(){
        return $this->hasMany(Expense::class,'account_id','id'); 
    }
}

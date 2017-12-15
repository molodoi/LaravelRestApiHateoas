<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as BaseUser;
use App\Models\Transaction;

class Buyer extends BaseUser
{
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}

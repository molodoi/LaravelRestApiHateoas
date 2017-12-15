<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as BaseUser;
use App\Models\Product;

class Seller extends BaseUser
{
    public function products(){
        return $this->hasMany(Product::class);
    }
}

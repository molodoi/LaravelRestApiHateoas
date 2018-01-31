<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as BaseUser;
use App\Models\Product;
use App\Scopes\SellerScope;

use App\Transformers\SellerTransformer;

class Seller extends BaseUser
{

	public $transformer = SellerTransformer::class;
	
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new SellerScope);
	}

    public function products(){
        return $this->hasMany(Product::class);
    }
}

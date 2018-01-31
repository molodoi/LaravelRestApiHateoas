<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as BaseUser;
use App\Models\Transaction;
use App\Transformers\BuyerTransformer;
use App\Scopes\BuyerScope;

class Buyer extends BaseUser
{

	public $transformer = BuyerTransformer::class;
	
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new BuyerScope);
	}

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

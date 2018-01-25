<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
    ];

    // remove pivot table from the results restfullapi.local/api/categories/1/products
    protected $hidden = [
    	'pivot'
    ];

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}

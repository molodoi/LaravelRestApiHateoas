<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        // List de categories pour les produits respectifs
        $categories = $buyer->transactions()->with('product.categories')->get()
        // uniquement les vendeurs avec pluck
        ->pluck('product.categories')
        // collapse = unique tableau avec toutes les categories
        ->collapse()
        // sans doublons to remove empty categories
        ->unique('id')
        ->values();

        return $this->showAll($categories);
    }
}
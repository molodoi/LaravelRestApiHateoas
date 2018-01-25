<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        // List de transaction avec leurs produits respectifs
        $products = $buyer->transactions()->with('product')->get()
        //unqiuement les produits avec pluck
        ->pluck('product');

        return $this->showAll($products);
    }
}
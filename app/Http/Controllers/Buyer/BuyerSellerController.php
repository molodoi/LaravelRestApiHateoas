<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        // List de transaction avec leurs produits et leurs vendeurs respectifs
        $sellers = $buyer->transactions()->with('product.seller')->get()
        //uniquement les vendeurs avec pluck
        ->pluck('product.seller')
        // sans doublons
        ->unique('id')
        ->values();

        return $this->showAll($sellers);
    }
}
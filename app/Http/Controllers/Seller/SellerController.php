<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Seller;

class SellerController extends ApiController
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
    public function index()
    {
        $sellers = Seller::has('products')->get();

        //return response()->json(['data' => $sellers], 200);
        //Instead use showAll from Traits/ApiResponse and by available in our BaseController named ApiController
        return $this->showAll($sellers);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //$seller = Seller::has('products')->findOrFail($id);
        // Instead we use Scopes/SellerScope and boot method in Models/Seller

        //return response()->json(['data' => $seller], 200);
        //Instead use showAll from Traits/ApiResponse and by available in our BaseController named ApiController
        return $this->showOne($seller);
    }
}

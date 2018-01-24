<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Seller;

class SellerController extends ApiController
{
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
    public function show($id)
    {
        $seller = Seller::has('products')->findOrFail($id);

        //return response()->json(['data' => $seller], 200);
        //Instead use showAll from Traits/ApiResponse and by available in our BaseController named ApiController
        return $this->showOne($seller);
    }
}

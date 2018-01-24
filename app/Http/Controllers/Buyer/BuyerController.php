<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Buyer;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyers = Buyer::has('transactions')->get();

        //return response()->json(['data' => $buyers], 200);
        //Use showAll method come from Traits/ApiResponse and by available in our BaseController named ApiController
        return $this->showAll($buyers);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function show($id)
    public function show(Buyer $buyer)
    {
        //$buyer = Buyer::has('transactions')->findOrFail($id);
        // Instead we use Scopes/BuyerScope and boot method in Models/Buyer

        //return response()->json(['data' => $buyer], 200);
        return $this->showOne($buyer);
    }

}

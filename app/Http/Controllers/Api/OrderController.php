<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class OrderController extends ApiController
{

    public function __construct()
    {
        $this->resource = OrderResource::class;
        $this->model = app(Order::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }


}

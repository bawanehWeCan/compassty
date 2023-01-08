<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CountryController extends ApiController
{
    public function __construct()
    {
        $this->resource = CountryResource::class;
        $this->model = app(Country::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }
}

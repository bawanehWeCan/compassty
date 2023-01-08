<?php

namespace App\Http\Controllers\Api;

use App\Models\Introduction;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\IntroductionRequest;
use App\Http\Resources\IntroductionResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class IntroductionController extends ApiController
{
    public function __construct()
    {
        $this->resource = IntroductionResource::class;
        $this->model = app(Introduction::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }


}

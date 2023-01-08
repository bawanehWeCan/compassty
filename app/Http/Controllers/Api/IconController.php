<?php

namespace App\Http\Controllers\Api;

use App\Models\Icon;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\IconRequest;
use App\Http\Resources\IconResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class IconController extends ApiController
{

    public function __construct()
    {
        $this->resource = IconResource::class;
        $this->model = app(Icon::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }


}

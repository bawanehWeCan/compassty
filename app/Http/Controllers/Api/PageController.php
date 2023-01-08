<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\PageRequest;
use App\Http\Resources\PageResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PageController extends ApiController
{
    public function __construct()
    {
        $this->resource = PageResource::class;
        $this->model = app(Page::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }
}

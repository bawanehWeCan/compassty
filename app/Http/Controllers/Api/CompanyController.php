<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CompanyController extends ApiController
{
    public function __construct()
    {
        $this->resource = CompanyResource::class;
        $this->model = app(Company::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

    public function lookfor(Request $request){

        return $this->search('name',$request->value);

    }
}

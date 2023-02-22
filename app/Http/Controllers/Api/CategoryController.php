<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->resource = CategoryResource::class;
        $this->model = app(Category::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

    public function viewCompanies($id,Request $request){
        $category = $this->model->with('companies')->first();
        $except =[];
        foreach ($category->companies as $company) {
            $lat1 = $request->lat;
            $lon1 = $request->long;
            $lat2 = $company->lat;
            $lon2 = $company->long;
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                array_push($except,$company->id);
              }
              else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $kiloes = $dist * 60 * 1.1515 * 1.609344;
                if ($kiloes > 10) {
                    array_push($except,$company->id);
                }
              }

        }
        if (count($except)>0) {
            $category->load(['companies'=>fn($q)=>$q->whereNotIn('id',$except)]);
        }else{
            $category;
        }
        return $this->returnData('data', new $this->resource($category), __('Updated succesfully'));
    }


}


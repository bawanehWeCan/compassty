<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class NotificationController extends ApiController
{
    public function __construct()
    {
        $this->resource = NotificationResource::class;
        $this->model = app(Notification::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

    public function view($id)
    {
        $data=$this->model->find($id);
        $data->view = 1;
        $data->save();
        return $this->returnData('data', new $this->resource($data), __('Updated succesfully'));
    }

    public function listView()
    {

        $data =  $this->repositry->latest()->update(['view'=>1]);
        $data =  $this->repositry->latest()->get();
        return $this->returnData( 'data' , $this->resource::collection( $data ), __('Succesfully'));


    }

}


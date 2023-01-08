<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Request;

class ApiController extends Controller
{

    use ResponseTrait;


    /**
     * Create new Library class.
     *
     * this abstraction expects the child class to have a protected attribute named model.
     * that will hold the model name with its full namespace.
     */
    public function __construct($repositry, $resource, $model)
    {
        $this->repositry =  $repositry;
        $this->resource = $resource;
        $this->model = $model;
    }


    public function list()
    {

        $data =  $this->repositry->all();

        return $this->returnData( 'data' , $this->resource::collection( $data ), __('Succesfully'));


    }


    public function listWithCondition($key,$value)
    {

        $data =  $this->repositry->allWithCondition($key,$value);

        return $this->returnData( 'data' , $this->resource::collection( $data ), __('Succesfully'));


    }

    public function pagination( $lenght = 10 )
    {

        $data =  $this->repositry->pagination( $lenght );

        return $this->returnData( 'data' , $this->resource::collection( $data ), __('Succesfully'));


    }


    /**
     * store function
     *
     * @param Request $request
     * @return void
     */
    public function store( $data )
    {
        $model = $this->repositry->save( $data );


        if ($model) {
            return $this->returnData( 'data' , new $this->resource( $model ), __('Succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create !'));
    }


    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function view($id)
    {
        $model = $this->repositry->getByID($id);

        if ($model) {
            return $this->returnData('data', new $this->resource( $model ), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $model = $this->repositry->getByID($id);

        if (!$model) {
            return $this->returnError(__('Sorry! Failed to get !'));
        }

        $this->repositry->deleteByID($id);

        return $this->returnSuccessMessage(__('Delete succesfully!'));
    }

    public function search($key,$value){

        $data = $this->repositry->searchManyByKey($key,$value);

        if ($data) {
            return $this->returnData('data', $this->resource::collection( $data ), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));


    }


    public function update($id,$data){

        $model = $this->repositry->getByID($id);
        if ($model) {
            $model = $this->repositry->edit( $id,$data );
            return $this->returnData('data', new $this->resource( $model ), __('Updated succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));

    }


}

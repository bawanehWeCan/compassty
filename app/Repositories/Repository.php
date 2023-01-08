<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class Repository{


    /**
     * holds the specific model itself.
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Create new Library class.
     *
     * this abstraction expects the child class to have a protected attribute named model.
     * that will hold the model name with its full namespace.
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
    }


    /**
     * @return void
     */
    public function all(){
        $data = $this->model->get();
        return $data;
    }



    public function allWithCondition($key, $value){
        $data = $this->model->where( $key,$value)->get();
        return $data;
    }

        /**
     * @return void
     */
    public function pagination($length = 10): LengthAwarePaginator
    {
        $data = $this->model->paginate($length);
        return $data;
    }


    /**
     * @param [type] $model_id
     * @return void
     */
    function getByID( $model_id )
    {
        $model = $this->model->find($model_id);
        return $model;
    }

    /**
     * delete model by id
     *
     * @param [type] $model_id
     * @param boolean $force
     * @return void
     */
    public function deleteByID( $model_id, bool $force = false ):void
    {
        $model = $this->model->find($model_id);

        if ($force) {
            $model->forceDelete();
        }

        if (! $force) {
            $model->delete();
        }
    }


    /**
     * @return void
     */
    function save( $data )
    {
        $model = $this->model->create( $data );
        return $model->fresh();
    }


    public function searchManyByKey($key, $value){
        $data = $this->model->where( $key, 'like', '%' . $value . '%' )->get();
        return $data;
    }

    public function edit($id,$data){
        $model = $this->model->where( 'id', $id )->firstOrFail();
        $model->update($data);
        return $model->fresh();

    }

}

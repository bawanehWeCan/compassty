<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Repositories\Repository;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ProfileUpdateRequest;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->resource = UserResource::class;
        $this->model = app(User::class);
        $this->repositry =  new UserRepository($this->model);
    }

    public function save( UserRequest $request ){
        try {
            DB::beginTransaction();
            $user = $this->repositry->save($request);
            // if ($request->has('image')) {
            //     $this->repositry->insertImage($request->image,$user);
            // }
            DB::commit();
            return $this->returnData('user', new $this->resource($user),'User created Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
            return $this->returnError('Sorry! Failed in creating user');
        }
    }
    public function edit( $id,ProfileUpdateRequest $request ){
        try {
            DB::beginTransaction();
            $user = $this->model->find($id);
            // check unique email except this user
            if (isset($request->email)) {
                $check = User::where('email', $request->email)
                    ->first();

                if ($check) {

                    return $this->returnError('The email address is already used!');
                }
            }




            $this->repositry->edit($request,$user);

            // if ($request->has('image') && $user->has('image')) {
            //     $image = $this->repositry->insertImage($request->image,$user,true);
            // }elseif ($request->has('image')) {
            //     $image = $this->repositry->insertImage($request->image,$user);
            // }

            DB::commit();
            // unset($user->image);
            return $this->returnData('user', new $this->resource($user), 'User updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('Sorry! Failed in updating user');
        }
    }

}

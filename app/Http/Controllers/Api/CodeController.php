<?php

namespace App\Http\Controllers\Api;

use App\Models\Code;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\CodeRequest;
use App\Http\Resources\CodeResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Str;

class CodeController extends ApiController
{
    public function __construct()
    {
        $this->resource = CodeResource::class;
        $this->model = app(Code::class);
        $this->repositry =  new Repository($this->model);
    }


    public function sellCode(Request $request)
    {


        $data = Code::where('code', $request->code)->first();

        $country_code = Country::where('id', $request->country_id)->pluck('code')->first();
        $city_code = City::where('id', $request->city_id)->pluck('code')->first();

        $country_city = strtoupper($country_code . $city_code);

        if ($data) {

            $newCode = str_replace ('UNIQUE',$country_city, $data->code);



            $data->update([
                'code'=>$newCode,
                'type'=>'premium',
                'user_id' => $request->user_id
            ]);

            $address = Address::find($request->address_id);
            $address->code_id = $data->id;
            $address->save();

            $data->load('address');
            return $this->returnData('data', new $this->resource($data), __('Updated succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\AddressResource;
use App\Http\Resources\MyAddressResource;
use App\Models\Address;
use App\Models\Code;
use App\Models\Country;
use App\Models\City;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Image;
use Illuminate\Support\Str;
use Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class AddressController extends ApiController
{

    public function __construct()
    {
        $this->resource = AddressResource::class;
        $this->model = app(Address::class);
        $this->repositry = new Repository($this->model);
    }

    public function save(AddressRequest $request)
    {


        try {

           // DB::beginTransaction();
            $country_id = $request->country_id;
            $city_id = $request->city_id;

            $country_code = Country::where('id', $country_id)->pluck('code')->first();
            $city_code = City::where('id', $city_id)->pluck('code')->first();


            $country_city = strtoupper($country_code . $city_code);

            $random_number = random_int(1000, 9999);
            $rn = (string) $random_number;


            if ($this->checkUniqueNumber($rn)) {

                $code = Code::Where('code', 'UNIQUE' . $rn)->first();
                if (empty($code) || !$code) {
                    $code = new Code();
                    $code->code = 'UNIQUE' . $rn;
                    $code->type = 'personal';
                    $code->save();
                }



                return $this->save($request);
            } else {


                $code_rn = str($country_city)->append($rn);
                $code = Code::Where('code', $code_rn)->first();

                if (empty($code) || !$code) {
                    $code = new Code();
                    $code->id = $request->id;
                    $code->code = $code_rn;
                    $code->type = 'personal';
                    $code->save();
                } else {
                    return $this->save($request);
                }


                $request['code_id'] = $code->id;
                $user = Auth::user();
                $request['user_id'] = $user->id;

                $address = new Address();
                $address->icon_id = $request->icon_id;
                $address->name = $request->name;
                $address->phone_number = $request->phone_number;
                $address->lat = $request->lat;
                $address->long = $request->long;
                $address->region = $request->region;
                $address->street = $request->street;
                $address->build_number = $request->build_number;
                $address->house_number = $request->house_number;
                $address->floor_number = $request->floor_number;
                $address->note = $request->note;
                $address->code_id = $request->code_id;
                $address->country_id = $request->country_id;
                $address->city_id = $request->city_id;
                $address->user_id = $request->user_id;
                $address->save();
                if( !empty( $request->images ) ){
                foreach ($request->images as $image) {
                    # code...

                    $i = new Image();
                    $i->image = $image;
                    $i->address_id = $address->id;
                    $i->company_id = 0;
                    $i->save();

                }}



                // $address = $this->repositry->save($request->all());

                return $this->returnData('data', new $this->resource($address), __('Get  succesfully'));
            }
            //DB::commit();
        } catch (Exception $ex) {
            // dd( $ex );
           // Db::rollBack();

            return $this->returnError('not created !!');
        }
    }

    public function edit($id, AddressRequest $request)
    {

        return $this->update($id, $request->all());
    }

    public function view($code)
    {

        $data = Code::where('code', $code)->first();


        if (empty($data->id)) {

            return $this->returnError('Address not found !!!');
        }

        if (Auth::user()) {
            Auth::user()->recent()->save($data->address);
        }

        // echo $data->name; // getNameAttribute()
        // return;
        return $this->returnData('data', new $this->resource($data->address), __('Get  succesfully'));
    }

    public function myAddresses(Request $request)
    {

        $user = Auth::user();

        return $this->returnData('data', MyAddressResource::collection($user->addresses), __('Get  succesfully'));
    }


    public function recentAddresses(Request $request)
    {

        $user = Auth::user();

        return $this->returnData('data', MyAddressResource::collection($user->recent), __('Get  succesfully'));
    }

    public function checkUniqueNumber($rn)
    {

        if (($rn[1] == $rn[0] + 1 ||
            $rn[2] == $rn[1] + 1 ||
            $rn[3] == $rn[2] + 1) || ($rn[1] == $rn[0] - 1 ||
            $rn[2] == $rn[1] - 1 ||
            $rn[3] == $rn[2] - 1) || ($rn[0] == $rn[1] ||
            $rn[1] == $rn[2] ||
            $rn[2] == $rn[3])) {
            return true;
        }

        return false;
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'icon_id'=>'required|exists:icons,id',
            'name'=>'required|min:4|max:255',
            'phone_number'=> 'required|min:9|regex:/^([0-9\s\-\+\(\)]*)$/|unique:addresses,phone_number,'.$this->id,
            'lat'=>'required|numeric',
            'long'=>'required|numeric',
            'region'=>'required|min:4|max:255',
            'street'=>'required|min:4|max:255',
            'build_number'=>'required|numeric',
            'house_number'=>'required|numeric',
            'floor_number'=>'required|numeric',
            'note'=>'required|min:4|max:2000',
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
            'user_id'=>'required|exists:users,id',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'icon_id'=>'icon',
            'phone_number'=>'phone',
            'build_number'=>'build',
            'house_number'=>'house',
            'floor_number'=>'floor',
            'country_id'=>'country',
            'city_id'=>'city',
            'user_id'=>'user',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}

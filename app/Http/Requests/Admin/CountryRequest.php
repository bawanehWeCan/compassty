<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
            'en'=>"required|min:4|max:255",
            'ar'=>"required|min:4|max:255",
            'code' => 'required|min:2|max:255',
            'skip_otp'=>'required|in:1,0',
            'digits'=>'nullable|numeric',
            'image'=>'required_without:id|mimes:jpg,gif,jpeg,png',
            'city_en'=>"nullable|min:4|max:255",
            'city_ar'=>[Rule::requiredIf(!empty($this->city_en)),"nullable","min:4","max:255"],
            'city_code' => [Rule::requiredIf(!empty($this->city_en)),"nullable","min:2","max:255"],

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
            'en' => 'english name',
            'ar' => 'arabic name',
            'city_en' => 'city in english',
            'city_ar' => 'city in arabic',
            'city_code' => 'city code',
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

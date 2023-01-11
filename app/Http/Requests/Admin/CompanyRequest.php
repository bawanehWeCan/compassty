<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'en' => 'required|min:5|max:255',
            'ar' => 'required|min:5|max:255',
            'address_en' => 'required|min:5|max:255',
            'address_ar' => 'required|min:5|max:255',
            'description_en' => 'required|min:5|max:10000',
            'description_ar' => 'required|min:5|max:10000',
            'short_desc_en' => 'required|min:5|max:2000',
            'short_desc_ar' => 'required|min:5|max:2000',
            'phone' => 'required|min:9|regex:/^([0-9\s\-\+\(\)]*)$/|unique:companies,phone,'.$this->id,
            'category_id' => 'required|exists:categories,id',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'logo'=>'required_without:id|mimes:jpg,gif,jpeg,png',
            'cover_picture'=>'required_without:id|mimes:jpg,gif,jpeg,png',
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
            'address_en' => 'english address',
            'address_ar' => 'arabic address',
            'description_en' => 'english description',
            'description_ar' => 'arabic description',
            'short_desc_en' => 'english short description',
            'short_desc_ar' => 'arabic short description',
            'category_id' => 'category',
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

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class IntroductionRequest extends FormRequest
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
            'body_en' => 'required|min:5|max:10000',
            'body_ar' => 'required|min:5|max:255',
            'image'=>'required_without:id|mimes:jpg,gif,jpeg,png',
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
            'en' => 'english title',
            'ar' => 'arabic title',
            'body_en' => 'english body',
            'body_ar' => 'arabic body',
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

<?php

namespace App\Http\Requests\Admin;

use App\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required | string ',
            'email' => 'required|email|unique:users,email,'.$this->id,
            'password' => 'required|min:8',
            'phone' => 'required|min:9|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,phone,'.$this->id,
            'type' => 'required|in:admin,user',

        ];
    }

}

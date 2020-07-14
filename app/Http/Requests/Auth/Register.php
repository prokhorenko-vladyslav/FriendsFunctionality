<?php

namespace App\Http\Requests\Auth;

use App\Traits\Requests\CustomValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
{
    use CustomValidationResponse;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string:40|alpha',
            'last_name' => 'required|string:40|alpha',
            'email' => 'required|string:100|email:rfc,dns|unique:users,email',
            'password' => 'required|string:100|confirmed|min:6',
        ];
    }
}

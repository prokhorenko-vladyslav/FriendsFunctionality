<?php

namespace App\Http\Requests\Friend;

use App\Traits\Requests\CustomValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
            'friend_id' => 'required|numeric|exists:users,id'
        ];
    }
}

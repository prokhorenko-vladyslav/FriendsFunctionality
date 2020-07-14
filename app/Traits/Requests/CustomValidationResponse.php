<?php


namespace App\Traits\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Trait for handle validation exceptions and return response via responder
 *
 * Trait CustomValidationResponse
 * @package App\Traits
 */
trait CustomValidationResponse
{
    /**
     * If request has been send via ajax, all validation errors will be send using responder
     *
     * @param Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator) {
        if (request()->wantsJson()) {
             throw new HttpResponseException(
                responder()->error(422, 'Fields is invalid')->data([
                    'data' => $validator->errors()->messages()
                ])->respond(422)
             );
        } else {
            parent::failedValidation($validator);
        }
    }
}

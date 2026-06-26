<?php

namespace App\Http\Requests\Api\Auth;

use App\Rules\EmailNotExistRule;
use App\Constants\Declare\HttpCode;
use App\Constants\Declare\HttpStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
{
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
            'email' => [
                'required',
                'email',
                new EmailNotExistRule
            ],
            'token' => 'required',
            'password' => 'required|confirmed'
        ];
    }

    /**
     * Handles validation error
     * 
     * @param Validator
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(
                responder()
                    ->error(HttpCode::VALIDATION_FAILED, trans('validation.failed'))
                    ->data([
                        'validation_errors' => $errors
                    ])
                    ->respond(HttpStatus::MISDIRECTED_REQUEST)
            );
        }

        parent::failedValidation($validator);
    }
}

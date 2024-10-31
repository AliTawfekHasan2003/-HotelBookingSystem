<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|min:3|max:15|regex:/^[\p{Arabic}\p{Latin}][\p{Arabic}\p{Latin}\d_]{2,14}$/u',
            'last_name' => 'required|string|min:3|max:15|regex:/^[\p{Arabic}\p{Latin}][\p{Arabic}\p{Latin}\d_]{2,14}$/u',
            'email' => 'required|string|email|max:64|unique:users|regex:/^[a-zA-Z0-9._%+-]{3,}@gmail\.com$/',
            'password' => 'required|string|confirmed|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-z])(?=.*[\*\@\.\-\+])[a-zA-Z0-9\*\@\.\-\+\#\&]{8,20}$/',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => __('validation.required'),
            'first_name.string' => __('validation.string'),
            'first_name.min' => __('validation.min.string', ['min' => 3]),
            'first_name.max' => __('validation.max.string', ['max' => 15]),
            'first_name.regex' => __('validation.regex.first_name'),
            'last_name.required' =>  __('validation.required'),
            'last_name.string' => __('validation.string'),
            'last_name.min' => __('validation.min.string', ['min' => 3]),
            'last_name.max' => __('validation.max.string', ['max' => 15]),
            'last_name.regex' => __('validation.regex.last_name'),
            'email.required' => __('validation.required'),
            'email.string' => __('validation.string'),
            'email.email' => __('validation.email'),
            'email.max' => __('validation.max.string', ['max' => 64]),
            'email.unique' => __('validation.unique.email'),
            'email.regex' => __('validation.regex.email'),
            'password.required' => __('validation.required'),
            'password.string' => __('validation.string'),
            'password.min' => __('validation.min.password', ['min' => 8]),
            'password.max' => __('validation.max.password', ['max' => 20]),
            'password.confirmed' => __('validation.confirmed.password'),
            'password.regex' => __('validation.regex.password'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->returnError($validator->errors(), 422);

        throw new HttpResponseException($response);
    }
}

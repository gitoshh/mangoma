<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class UserRequest extends RequestAbstract
{
    /**
     * Sanitize request data before validation.
     */
    protected function prepareForValidation()
    {
        $this->merge(['email' => strtolower($this->input('email'))]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|unique:users,email',
            'password' => 'required|min:6',
            'name'     => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'     => 'Email is required',
            'email.email'        => 'Email is of invalid format',
            'name.required'      => 'Name is required',
            'password.min'       => 'Password is too short',
            'password.required'  => 'Password is required',
        ];
    }
}

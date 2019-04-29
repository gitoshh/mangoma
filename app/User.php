<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static $rules = [
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required|string'
        ];

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
            'email.unique'       => 'Email already exists',
            'name.required'      => 'Name is required',
            'password.min'       => 'Password is too short',
            'password.required'  => 'Password is required',
        ];
    }
}

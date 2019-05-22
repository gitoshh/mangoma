<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table= 'roles';
    /**
     * @var array
     */
    public static $rules = [
        'name'        => 'required|string|unique:roles',
        'display_name'     => 'string',
        'description' => 'string',
    ];

}
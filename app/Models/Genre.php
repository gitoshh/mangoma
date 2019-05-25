<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = [
        'name',
    ];

    public static $rules = [
        'name' => 'string|required|unique:genres',
    ];

}
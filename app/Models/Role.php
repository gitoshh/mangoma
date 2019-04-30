<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * @var array
     */
    public static $rules = [
        'name'         => 'required|string|unique:roles',
        'display_name' => 'string',
        'description'  => 'string',
    ];
}

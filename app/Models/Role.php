<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $table = 'roles';

    /**
     * @var array
     */
    public static $rules = [
        'name'         => 'required|string|unique:roles',
        'display_name' => 'string',
        'description'  => 'string',
    ];
}

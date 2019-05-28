<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    /**
     * @var array
     */
    public static $rules = [
        'name'         => 'required|string|unique:permissions',
        'display_name' => 'string',
        'description'  => 'string',
    ];
}

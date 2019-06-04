<?php

namespace App\Domains;

use App\Permission;
use App\Role;

class Entrust
{
    /**
     * Adds a new role.
     *
     * @param string      $name
     * @param string|null $displayName
     * @param string|null $description
     *
     * @return array
     */
    public function newRole(
        string $name,
        ?string $displayName = null,
        ?string $description = null
    ): array {
        $newRole = new Role();
        $newRole->name = $name;
        $newRole->display_name = $displayName;
        $newRole->description = $description;
        if ($newRole->save()) {
            return $newRole->toArray();
        }
    }

    /**
     * Adds a new Permission.
     *
     * @param string      $name
     * @param string|null $displayName
     * @param string|null $description
     *
     * @return array
     */
    public function newPermission(
        string $name,
        ?string $displayName = null,
        ?string $description = null
    ): array {
        $newPermission = new Permission();
        $newPermission->name = $name;
        $newPermission->display_name = $displayName;
        $newPermission->description = $description;
        if ($newPermission->save()) {
            return $newPermission->toArray();
        }
    }
}

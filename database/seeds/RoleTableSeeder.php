<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $rolesTable = DB::table('roles');

        $rolesTable->insert([
            'name'         => 'Admin',
            'display_name' => 'Admin',
            'description'  => 'Admin role',
        ]);

        $rolesTable->insert([
            'name'         => 'Artiste',
            'display_name' => 'Artiste',
            'description'  => 'Artiste role',
        ]);

        $rolesTable->insert([
            'name'         => 'Normal',
            'display_name' => 'Normal',
            'description'  => 'Normal role',
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name'         => 'add-permission',
            'display_name' => 'Add permissions',
            'description'  => 'Permission to add permissions',
        ]);
    }
}

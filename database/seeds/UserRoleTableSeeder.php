<?php

use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('role_user')->insert([
            'user_id' => '1',
            'role_id' => '1',
        ]);
    }
}

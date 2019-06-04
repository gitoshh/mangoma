<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'     => 'test user',
            'email'    => 'test.user@gmail.com',
            'password' => Hash::make('A123123@'),
        ]);

        DB::table('users')->insert([
            'name'     => 'another user',
            'email'    => 'another.user@gmail.com',
            'password' => Hash::make('A123123@'),
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class PlaylistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('playlist')->insert([
            'title'   => 'test title',
            'creator' => 1,
        ]);
    }
}

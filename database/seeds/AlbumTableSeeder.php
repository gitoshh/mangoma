<?php


use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AlbumTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker\Factory::create();

        DB::table('album')->insert([
            'title'       => 'new album',
            'artistes'    => 'Jhene, Sean',
            'releaseDate' => Carbon::parse('12/12/2014')->toDate(),
        ]);

        DB::table('album')->insert([
            'title'       => $faker->title,
            'artistes'    => $faker->firstName,
            'releaseDate' => $faker->date(),
        ]);
    }
}

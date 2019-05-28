<?php


use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class MusicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $song = UploadedFile::fake()->create('music.mp3', 1300);

        DB::table('music')->insert([
            'title'        => 'new song',
            'artistes'     => 'Sean, Jhene',
            'genreId'      => 1,
            'location'     => $song->getPath(),
            'originalName' => $song->getClientOriginalName(),
            'extension'    => $song->getClientOriginalExtension(),
            'uniqueName'   => uniqid('audio_', true),
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicPlaylistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_playlist', function (Blueprint $table) {
            $table->integer('music_id')->unsigned();
            $table->integer('playlist_id')->unsigned();

            $table->foreign('music_id')->references('id')->on('music')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('playlist_id')->references('id')->on('playlist')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['music_id', 'playlist_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_playlist');
    }
}

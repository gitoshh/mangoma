<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('music_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('music_id')->references('id')->on('music')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('recommended_by');

            $table->primary(['user_id', 'music_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_user');
    }
}

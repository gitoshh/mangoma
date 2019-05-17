<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('comment', 255)->nullable();
            $table->smallInteger('rating')->nullable();
            $table->unsignedInteger('userId');
            $table->unsignedInteger('musicId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('musicId')->references('id')->on('music')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviegenresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('moviegenres', function (Blueprint $table) {
            $table->integer('movie_id')->unsigned();
            $table->integer('genre_id')->unsigned();
            $table->timestamps();

            $table->primary(['movie_id', 'genre_id']);

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->onDelete('cascade');
            $table->foreign('genre_id')
                ->references('id')
                ->on('genres')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('moviegenres');
    }
}

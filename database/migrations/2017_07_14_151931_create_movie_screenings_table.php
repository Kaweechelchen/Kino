<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieScreeningsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('movie_screening', function (Blueprint $table) {
            $table->integer('movie_id')->unsigned();
            $table->integer('screening_id')->unsigned();
            $table->string('theatre_id');
            $table->string('hall');
            $table->integer('format_id')->unsigned();
            $table->integer('language_id')->unsigned();
            $table->timestamps();

            $table->unique([
                'movie_id',
                'screening_id',
                'theatre_id',
                'hall',
            ]);

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->onDelete('cascade');
            $table->foreign('screening_id')
                ->references('id')
                ->on('screenings')
                ->onDelete('cascade');
            $table->foreign('theatre_id')
                ->references('id')
                ->on('theatres')
                ->onDelete('cascade');
            $table->foreign('format_id')
                ->references('id')
                ->on('formats')
                ->onDelete('cascade');
            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('movie_screening');
    }
}

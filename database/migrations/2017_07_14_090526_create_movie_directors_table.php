<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviedirectorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('moviedirectors', function (Blueprint $table) {
            $table->integer('movie_id')->unsigned();
            $table->string('director');
            $table->timestamps();

            $table->primary(['movie_id', 'director']);

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('moviedirectors');
    }
}

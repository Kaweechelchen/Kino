<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->longText('synopsis')->nullable()->default(null);
            $table->integer('length')->nullable()->default(null);
            $table->string('imdb')->nullable()->default(null);
            $table->integer('country_id')->unsigned()->nullable()->default(null);
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('genre');
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('genres');
    }
}

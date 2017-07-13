<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTheatresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('theatres', function (Blueprint $table) {
            $table->string('id');
            $table->string('theatre');
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('theatres');
    }
}

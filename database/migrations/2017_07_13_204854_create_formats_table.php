<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormatsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('formats', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('format');
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('formats');
    }
}

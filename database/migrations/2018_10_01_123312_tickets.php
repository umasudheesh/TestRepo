<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 250)->nullable();
            $table->string('timing', 100)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->integer('unique_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

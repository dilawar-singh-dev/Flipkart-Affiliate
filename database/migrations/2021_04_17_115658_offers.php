<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Offers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title',1000);
            $table->string('name',500);
            $table->string('start_time',500);
            $table->string('end_time',500);
            $table->binary('description')->nullable();
            $table->binary('fk_url');
           
            $table->string('image_default',500);
            $table->string('image_low',500);
            $table->string('image_mid',500);
            $table->string('image_high',500);


            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}

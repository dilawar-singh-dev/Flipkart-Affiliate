<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductSpecifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('product_specification_category_id');
            $table->index('product_specification_category_id');
            $table->foreign('product_specification_category_id')->references('id')->on('product_specification_categories')->onDelete('cascade');

            $table->string('title',300);
            
            $table->binary('description');

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
        Schema::dropIfExists('product_specifications');
    }
}

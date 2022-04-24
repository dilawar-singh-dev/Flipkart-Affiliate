<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('product_category_id');
            $table->index('product_category_id');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');

            $table->string('title',500);
            $table->string('slug',500);
            $table->binary('product_description')->nullable();
            $table->string('image_url_200x200',500);
            $table->string('image_url_400x400',500);
            $table->string('image_url_800x800',500);
            $table->string('fk_pid',200);
            $table->bigInteger('fk_selling_price');
            $table->string('fk_selling_currency',100);
            $table->binary('fk_product_url');
            $table->string('product_brand',300);
            $table->boolean('fk_in_stock',500);

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
        Schema::dropIfExists('products');
    }
}

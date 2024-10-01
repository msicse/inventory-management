<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producttype_id')->constrained('producttypes');
            $table->string('title');
            $table->string('brand');
            $table->string('slug');
            $table->string('model')->nullable();
            $table->string('unit')->nullable();
            $table->tinyInteger('is_serial')->default(2);
            $table->tinyInteger('is_license')->default(2);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('products');
    }
}

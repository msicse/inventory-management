<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('purchase_id')->constrained('purchases');
            $table->foreignId('producttype_id')->constrained('producttypes');
            $table->foreignId('pproduct_id')->constrained('purchase_products');
            $table->string('asset_tag')->nullable();
            $table->integer('serial_no')->nullable();
            $table->string('service_tag')->nullable();
            $table->string('mac')->nullable();
            $table->tinyInteger('product_status')->nullable();
            $table->string('warranty')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('expired_date')->nullable();
            $table->string('quantity')->nullable();
            $table->tinyInteger('assigned')->nullable();
            $table->tinyInteger('is_assigned');
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
        Schema::dropIfExists('stocks');
    }
}

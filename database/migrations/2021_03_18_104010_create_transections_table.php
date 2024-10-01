<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks');
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('issued_date')->nullable();
            $table->string('return_date')->nullable();
            $table->integer('quantity')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('transections');
    }
}

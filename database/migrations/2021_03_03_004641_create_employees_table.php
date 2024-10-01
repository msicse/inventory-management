<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments');
            $table->string('emply_id');
            $table->string('name');
            $table->string('designation');
            $table->string('phone');
            $table->string('email');
            $table->string('blood')->nullable();
            $table->string('gender');
            $table->string('location');
            $table->string('image')->nullable();
            $table->string('date_of_join')->nullable();
            $table->string('resign_date')->nullable();
            $table->tinyInteger('status');
            $table->text('about')->nullable();
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
        Schema::dropIfExists('employees');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products')) return;

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_consumable')) {
                // place after is_taggable when possible
                try {
                    $table->tinyInteger('is_consumable')->default(2)->after('is_taggable');
                } catch (\Exception $e) {
                    // fallback if 'after' is not supported for the connection
                    $table->tinyInteger('is_consumable')->default(2);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('products')) return;

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_consumable')) {
                $table->dropColumn('is_consumable');
            }
        });
    }
};

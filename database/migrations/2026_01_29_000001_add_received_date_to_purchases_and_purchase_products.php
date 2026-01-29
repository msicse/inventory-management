<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceivedDateToPurchasesAndPurchaseProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('purchases', 'received_date')) {
                $table->string('received_date')->nullable()->after('purchase_date');
            }
        });

        Schema::table('purchase_products', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_products', 'received_date')) {
                $table->string('received_date')->nullable()->after('purchase_date');
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
        Schema::table('purchase_products', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_products', 'received_date')) {
                $table->dropColumn('received_date');
            }
        });

        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'received_date')) {
                $table->dropColumn('received_date');
            }
        });
    }
}

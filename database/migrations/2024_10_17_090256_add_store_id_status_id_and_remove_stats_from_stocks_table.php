<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('product_status');
            $table->foreignId('status_id')->after('product_id')->constrained('asset_statuses');
            $table->foreignId('store_id')->after('product_id')->constrained('stores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['store_id']);
            $table->dropColumn('status_id');
            $table->dropColumn('store_id');
            $table->string('product_status')->nullable()->after('product_id');
        });
    }
};

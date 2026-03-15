<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('producttypes', function (Blueprint $table) {
            if (!Schema::hasColumn('producttypes', 'asset_class')) {
                $table->enum('asset_class', ['FIXED', 'CONSUMABLE'])
                    ->default('FIXED')
                    ->after('prefix');
                $table->index('asset_class');
            }
        });

        // Backfill: if any product in a type is marked consumable, mark the type as CONSUMABLE.
        DB::statement("\n            UPDATE producttypes pt\n            INNER JOIN (\n                SELECT producttype_id\n                FROM products\n                WHERE is_consumable = 1\n                GROUP BY producttype_id\n            ) p ON p.producttype_id = pt.id\n            SET pt.asset_class = 'CONSUMABLE'\n        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producttypes', function (Blueprint $table) {
            if (Schema::hasColumn('producttypes', 'asset_class')) {
                $table->dropIndex(['asset_class']);
                $table->dropColumn('asset_class');
            }
        });
    }
};

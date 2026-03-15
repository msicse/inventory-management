<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('producttypes')) {
            return;
        }

        Schema::table('producttypes', function (Blueprint $table) {
            if (!Schema::hasColumn('producttypes', 'prefix')) {
                $table->string('prefix', 4)->nullable()->after('name');
                $table->unique('prefix');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('producttypes')) {
            return;
        }

        Schema::table('producttypes', function (Blueprint $table) {
            if (Schema::hasColumn('producttypes', 'prefix')) {
                $table->dropUnique('producttypes_prefix_unique');
                $table->dropColumn('prefix');
            }
        });
    }
};

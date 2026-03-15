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
            if (!Schema::hasColumn('producttypes', 'parent_id')) {
                $table->foreignId('parent_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('producttypes')
                    ->nullOnDelete();
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
            if (Schema::hasColumn('producttypes', 'parent_id')) {
                $table->dropConstrainedForeignId('parent_id');
            }
        });
    }
};

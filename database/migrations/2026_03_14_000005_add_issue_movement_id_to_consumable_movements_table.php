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
        Schema::table('consumable_movements', function (Blueprint $table) {
            if (!Schema::hasColumn('consumable_movements', 'issue_movement_id')) {
                $table->foreignId('issue_movement_id')
                    ->nullable()
                    ->after('transection_id')
                    ->constrained('consumable_movements')
                    ->nullOnDelete();

                $table->index(['issue_movement_id', 'movement_type']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumable_movements', function (Blueprint $table) {
            if (Schema::hasColumn('consumable_movements', 'issue_movement_id')) {
                $table->dropConstrainedForeignId('issue_movement_id');
            }
        });
    }
};

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
        $tables = ['products', 'categories', 'brands', 'transactions', 'stock_movements', 'settings'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->nullOnDelete();
            });
        }

        // Adjust settings unique constraint
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key']);
            $table->unique(['key', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key', 'branch_id']);
            $table->unique(['key']);
        });

        $tables = ['products', 'categories', 'brands', 'transactions', 'stock_movements', 'settings'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            });
        }
    }
};

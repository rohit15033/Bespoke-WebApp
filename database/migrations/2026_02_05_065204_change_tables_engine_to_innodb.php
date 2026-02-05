<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'orders',
            'order_products',
            'order_packages',
            'order_sets',
            'order_items',
            'payment_records'
        ];

        foreach ($tables as $table) {
            DB::statement("ALTER TABLE `{$table}` ENGINE = InnoDB");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'orders',
            'order_products',
            'order_packages',
            'order_sets',
            'order_items',
            'payment_records'
        ];

        foreach ($tables as $table) {
            DB::statement("ALTER TABLE `{$table}` ENGINE = MyISAM");
        }
    }
};

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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('salesperson1_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('salesperson2_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['salesperson1_id']);
            $table->dropForeign(['salesperson2_id']);
            $table->dropColumn(['salesperson1_id', 'salesperson2_id']);
        });
    }
};

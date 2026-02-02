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
        Schema::table('appointments', function (Blueprint $table) {
            DB::statement("ALTER TABLE appointments MODIFY booking_status ENUM('Scheduled', 'Confirmed', 'Rescheduled', 'Canceled', 'Deal') NOT NULL DEFAULT 'Scheduled'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
             DB::statement("ALTER TABLE appointments MODIFY booking_status ENUM('Scheduled', 'Canceled', 'Deal') NOT NULL DEFAULT 'Scheduled'");
        });
    }
};

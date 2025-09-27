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
        Schema::create('kebaya_occasion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kebaya_id')->constrained('kebaya')->onDelete('cascade');
            $table->foreignId('occasion_id')->constrained('occasions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebaya_occasion');
    }
};

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
        Schema::create('kebaya', function (Blueprint $table) {
            $table->id();
    $table->string('code', 50)->unique();
    $table->string('name', 100);
    $table->foreignId('subcolor_id')->constrained('subcolors')->onDelete('cascade');
    $table->string('length', 50);
    $table->date('production_date'); 
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebaya');
    }
};

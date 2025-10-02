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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['kebaya', 'beskap', 'celana', 'selop', 'bustier', 'manset', 'hijab', 'veil', 'ekor', 'vest', 'dasi', 'kemeja', 'headwear', 'accesories']);
            $table->integer('production_month')->nullable();
            $table->integer('production_year')->nullable();
            $table->foreignId('subcolor_id')->constrained('subcolors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

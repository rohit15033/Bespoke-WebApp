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
        Schema::create('item_blueprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_blueprint_id')->constrained('set_blueprints')->onDelete('cascade');
            $table->string('description')->nullable(); // E.g., "Mother's Kebaya"
            $table->integer('sort_order')->default(0);
            $table->boolean('is_custom')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_blueprints');
    }
};

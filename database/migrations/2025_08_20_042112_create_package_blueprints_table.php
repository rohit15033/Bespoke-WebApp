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
        Schema::create('package_blueprints', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Asmara Dana Wedding Package"
            $table->decimal('default_price', 10, 2);
            $table->decimal('default_discount', 5, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_blueprints');
    }
};

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
        Schema::create('headwear_attributes_junctions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('headwear_id');
            $table->unsignedBigInteger('headwear_attribute_value_id');

            $table->foreign('headwear_id', 'fk_hw_junction_headwear')
                ->references('id')->on('headwears')
                ->onDelete('cascade');

            $table->foreign('headwear_attribute_value_id', 'fk_hw_junction_value')
                ->references('id')->on('headwear_attribute_values')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('headwear_attributes_junctions');
    }
};

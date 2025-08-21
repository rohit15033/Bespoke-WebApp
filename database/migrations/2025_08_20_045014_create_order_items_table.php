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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_set_id')->nullable()->constrained('order_sets')->onDelete('cascade');
            $table->string('item_sku')->nullable(); // FK to items.sku, NULLABLE
            $table->text('note')->nullable();
            $table->string('status')->nullable(); // "active" or "removed"
            $table->boolean('is_additional')->default(false);
            $table->boolean('is_custom')->default(false);
            $table->boolean('is_tentative')->nullable()->default(false);
            $table->string('rental_status'); // "rent" or "purchase"
            $table->string('default_item_type')->nullable();
            $table->decimal('price', 12, 2)->nullable(); // Only populated for standalone items
            $table->decimal('discount', 12, 2)->nullable(); // Only populated for standalone items
            $table->string('custom_name')->nullable(); // For custom item details
            $table->string('custom_type')->nullable(); // For custom item details
            $table->text('custom_details')->nullable(); // For custom item details
            $table->integer('sort_order')->default(0); // For ordering
            $table->timestamps();
            
            // Add foreign key constraint for item_sku
            $table->foreign('item_sku')->references('sku')->on('items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

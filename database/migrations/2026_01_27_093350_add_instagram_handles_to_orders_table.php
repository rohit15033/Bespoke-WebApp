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
            $table->string('instagram_bride')->nullable();
            $table->string('instagram_groom')->nullable();
            $table->string('instagram_mua')->nullable();
            $table->string('instagram_hairdo')->nullable();
            $table->string('instagram_accessories')->nullable();
            $table->string('instagram_photography')->nullable();
            $table->string('instagram_wo')->nullable();
            $table->string('instagram_decor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'instagram_bride',
                'instagram_groom',
                'instagram_mua',
                'instagram_hairdo',
                'instagram_accessories',
                'instagram_photography',
                'instagram_wo',
                'instagram_decor',
            ]);
        });
    }
};

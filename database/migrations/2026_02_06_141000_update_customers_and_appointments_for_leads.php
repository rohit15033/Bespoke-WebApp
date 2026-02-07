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
        Schema::table('customers', function (Blueprint $table) {
            $table->timestamp('first_whatsapp_interaction_at')->nullable()->after('notes');
            $table->timestamp('first_staff_reply_at')->nullable()->after('first_whatsapp_interaction_at');
            $table->json('source_meta')->nullable()->after('source');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->json('outcome_reasons')->nullable()->after('result_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['first_whatsapp_interaction_at', 'first_staff_reply_at', 'source_meta']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['outcome_reasons']);
        });
    }
};

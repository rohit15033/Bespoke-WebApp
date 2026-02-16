<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed the default WhatsApp template
        DB::table('settings')->insert([
            'key' => 'whatsapp_template',
            'value' => "Thank you for contacting BERKAT KEBAYA.\n\nNama : \nTanggal acara : \nInformasi yang di butuhkan : \nBerkenankah di jadwalkan mampir ke boutique ? : ( Tanggal  & ⏰ ) :",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

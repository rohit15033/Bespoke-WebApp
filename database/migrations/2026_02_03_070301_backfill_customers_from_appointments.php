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
        // Backfill from Appointments
        $appointments = \App\Models\Appointments::all();
        foreach ($appointments as $appointment) {
            if (!$appointment->customer_name || !$appointment->customer_phone) continue;

            $customer = \App\Models\Customer::firstOrCreate(
                [
                    'name' => $appointment->customer_name,
                    'phone' => $appointment->customer_phone,
                ],
                [
                    'email' => null,
                    'address' => null,
                    'notes' => null,
                ]
            );

            $appointment->customer_id = $customer->id;
            $appointment->save();
        }

        // Backfill from Orders
        $orders = \App\Models\Order::all();
        foreach ($orders as $order) {
            if (!$order->customer_name || !$order->customer_phone_number) continue;

            $customer = \App\Models\Customer::firstOrCreate(
                [
                    'name' => $order->customer_name,
                    'phone' => $order->customer_phone_number,
                ],
                [
                    'email' => null,
                    'address' => $order->customer_address, // Use address from order if creating new profile
                    'notes' => null,
                ]
            );

            // If customer existed but had no address, and this order has one, maybe update it?
            // For now, keep it simple. Only set address on creation.

            $order->customer_id = $customer->id;
            $order->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};

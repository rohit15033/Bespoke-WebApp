<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['draft', 'confirmed', 'completed', 'cancelled'];
        $totalPrice = $this->faker->randomFloat(2, 500000, 5000000);
        $totalDiscount = $this->faker->randomFloat(2, 0, $totalPrice * 0.2); // Max 20% discount
        $finalPrice = $totalPrice - $totalDiscount;
        
        return [
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(1000, 9999),
            'status' => $this->faker->randomElement($statuses),
            'customer_name' => $this->faker->name(),
            'customer_address' => $this->faker->address(),
            'customer_phone_number' => $this->faker->phoneNumber(),
            'event_place' => $this->faker->city() . ' Convention Center',
            'event_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'total_price' => $totalPrice,
            'total_discount' => $totalDiscount,
            'final_price' => $finalPrice,
        ];
    }
}

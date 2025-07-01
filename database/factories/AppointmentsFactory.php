<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointments>
 */
class AppointmentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'booking_status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'customer_name' => $this->faker->name,
            'customer_phone' => $this->faker->phoneNumber,
            'at' => $this->faker->dateTimeBetween('+1 days', '+10 days')->format('Y-m-d H:i:s'),
            'notes' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

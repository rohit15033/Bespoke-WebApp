<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\SubColors;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Beskap>
 */
class BeskapFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'        => $this->faker->unique()->bothify('KB###'),  // e.g., KB123
            'name'        => $this->faker->words(2, true),      
            'type'      => $this->faker->randomElement(['Jawa', 'Nasional']),
            'production_year' => $this->faker->numberBetween(2023,2025), 
            'production_month' => $this->faker->monthName(),
            'subcolor_id' => SubColors::inRandomOrder()->first()->id ?? SubColors::factory(),
        ];
    }
}

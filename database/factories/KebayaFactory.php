<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kebaya;
use App\Models\Colors;
use App\Models\SubColors;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\kebaya>
 */
class KebayaFactory extends Factory
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
            'subcolor_id' => SubColors::inRandomOrder()->first()->id ?? SubColors::factory(),
            'length'      => $this->faker->randomElement(['Short', 'Medium', 'Long']),
            'production_date' => $this->faker->dateTimeBetween('-1 years', 'now'), 

        ];
    }
}

<?php

namespace Database\Factories;
use App\Models\SubColors;
use App\Models\Colors;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubColors>
 */
class SubColorsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SubColors::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->colorName(),
            'color_id' => Colors::factory(),
        ];
    }
}

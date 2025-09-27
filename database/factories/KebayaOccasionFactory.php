<?php

namespace Database\Factories;
use App\Models\Kebaya;
use App\Models\Occasions;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KebayaOccasion>
 */
class KebayaOccasionFactory extends Factory
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
            'kebaya_id' => Kebaya::inRandomOrder()->first()->id ?? Kebaya::factory(),
            'occasion_id' => Occasions::inRandomOrder()->first()->id ?? Occasions::factory(),
        ];
    }
}

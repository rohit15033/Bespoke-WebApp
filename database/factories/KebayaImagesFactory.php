<?php

namespace Database\Factories;

use App\Models\Kebaya;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KebayaImages>
 */
class KebayaImagesFactory extends Factory
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
            'kebaya_id' => Kebaya::inRandomOrder()->first()->id,
            'image_url' => $this->faker->imageUrl('fashion', true),
        ];
    }
}

<?php

namespace Database\Factories;
use App\Models\Occasions;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\occasions>
 */
class OccasionssFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Occasions::class;
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->word(),
        ];
    }
}

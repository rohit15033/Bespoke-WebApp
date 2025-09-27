<?php

namespace Database\Factories;

use App\Models\Colors;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Colors>
 */
class ColorsFactory extends Factory
{
    protected $model = Colors::class;

    public function definition()
    {
        return [
            'name' => $this->faker->safeColorName(),
        ];
    }
}

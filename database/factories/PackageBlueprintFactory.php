<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PackageBlueprint>
 */
class PackageBlueprintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $packageNames = [
            'Asmara Dana Wedding Package',
            'Traditional Ceremony Package',
            'Modern Wedding Package',
            'Cultural Event Package',
            'Graduation Ceremony Package',
            'Corporate Event Package'
        ];
        
        return [
            'name' => $this->faker->randomElement($packageNames),
            'default_price' => $this->faker->randomFloat(2, 500000, 5000000),
            'default_discount' => $this->faker->randomFloat(2, 0, 20),
            'note' => $this->faker->optional(0.7)->paragraph(),
        ];
    }
}

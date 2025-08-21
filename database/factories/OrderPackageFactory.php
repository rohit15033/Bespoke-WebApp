<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderPackage>
 */
class OrderPackageFactory extends Factory
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
        
        $price = $this->faker->randomFloat(2, 500000, 3000000);
        $discount = $this->faker->randomFloat(2, 0, $price * 0.15); // Max 15% discount
        
        return [
            'name' => $this->faker->randomElement($packageNames),
            'price' => $price,
            'discount' => $discount,
            'note' => $this->faker->optional(0.7)->paragraph(),
        ];
    }
}

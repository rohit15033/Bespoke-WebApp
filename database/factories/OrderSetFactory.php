<?php

namespace Database\Factories;

use App\Models\OrderPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderSet>
 */
class OrderSetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $setNames = [
            "Man's Set",
            "Woman's Set",
            "Bride's Set",
            "Groom's Set",
            "Accessories Set",
            "Complete Set"
        ];
        
        return [
            'order_package_id' => OrderPackage::factory(),
            'name' => $this->faker->randomElement($setNames),
        ];
    }
}

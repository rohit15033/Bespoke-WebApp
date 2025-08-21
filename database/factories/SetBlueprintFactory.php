<?php

namespace Database\Factories;

use App\Models\PackageBlueprint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SetBlueprint>
 */
class SetBlueprintFactory extends Factory
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
            'package_blueprint_id' => PackageBlueprint::factory(),
            'name' => $this->faker->randomElement($setNames),
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}

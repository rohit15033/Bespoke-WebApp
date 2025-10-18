<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SetBlueprint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemBlueprint>
 */
class ItemBlueprintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $itemTypes = ['Kebaya', 'Beskap', 'Dress', 'Blouse', 'Pants', 'Skirt', 'Accessory'];
        
        return [
            'set_blueprint_id' => SetBlueprint::factory(),
            'item_type' => $this->faker->randomElement($itemTypes),
            'placeholder_sku' => Product::factory(),
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}

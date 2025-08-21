<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\OrderSet;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $itemTypes = ['Kebaya', 'Beskap', 'Dress', 'Blouse', 'Pants', 'Skirt', 'Accessory'];
        $rentalStatuses = ['rent', 'purchase'];
        $statuses = ['active', 'removed'];
        $isCustom = $this->faker->boolean(20); // 20% chance of being custom
        
        $price = $this->faker->randomFloat(2, 100000, 1000000);
        $discount = $this->faker->randomFloat(2, 0, $price * 0.1); // Max 10% discount
        
        return [
            'order_set_id' => OrderSet::factory(),
            'item_sku' => $isCustom ? null : Item::factory(),
            'note' => $this->faker->optional(0.6)->sentence(),
            'status' => $this->faker->randomElement($statuses),
            'is_additional' => $this->faker->boolean(30), // 30% chance of being additional
            'is_custom' => $isCustom,
            'rental_status' => $this->faker->randomElement($rentalStatuses),
            'default_item_type' => $this->faker->randomElement($itemTypes),
            'price' => $price,
            'discount' => $discount,
            'custom_name' => $isCustom ? $this->faker->words(3, true) : null,
            'custom_type' => $isCustom ? $this->faker->randomElement($itemTypes) : null,
            'custom_details' => $isCustom ? $this->faker->paragraph() : null,
        ];
    }
}

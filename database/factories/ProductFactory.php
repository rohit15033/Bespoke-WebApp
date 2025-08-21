<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Kebaya', 'Beskap', 'Dress', 'Blouse', 'Pants', 'Skirt'];
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White', 'Gold', 'Silver', 'Purple', 'Pink', 'Orange'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        $type = $this->faker->randomElement($types);
        $color = $this->faker->randomElement($colors);
        $size = $this->faker->randomElement($sizes);
        
        // Generate SKU based on type, color, and size
        $sku = strtoupper(substr($type, 0, 3)) . '-' . strtoupper(substr($color, 0, 2)) . '-' . $size;
        
        return [
            'sku' => $sku,
            'name' => $this->faker->words(3, true),
            'type' => $type,
            'color' => $color,
            'size' => $size,
            'image_url' => $this->faker->imageUrl(400, 600, 'fashion'),
        ];
    }
}

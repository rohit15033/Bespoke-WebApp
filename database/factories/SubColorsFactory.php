<?php

namespace Database\Factories;

use App\Models\SubColors;
use App\Models\Colors;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubColorsFactory extends Factory
{
    protected $model = SubColors::class;

    public function definition(): array
    {
        // Final real sub-color mapping based on your list
        $colorMap = [
            'Biru' => [
                'Biru Donker / Navy', 'Biru Muda', 'Sky Blue', 'Royal Blue',
                'Turquoise', 'Tosca', 'Electric Blue'
            ],
            'Merah' => [
                'Maroon', 'Merah Cabe'
            ],
            'Gold' => [
                'Gold Tua', 'Gold Muda', 'Rose Gold', 'Champagne Gold', 'Yellow Gold'
            ],
            'Cokelat' => [
                'Cokelat Susu', 'Kopi', 'Nude Brown'
            ],
            'Hijau' => [
                'Army', 'Sage', 'Mint', 'Emerald', 'Tosca', 'Olive', 'Lumut', 'Botol'
            ],
            'Ungu' => [
                'Lavender', 'Lilac', 'Violet', 'Mauve', 'Magenta', 'Grape', 'Burgundy', 'Wine'
            ],
            'Hitam' => [
                'Jet Black', 'Metallic Black'
            ],
            'Putih' => [
                'Snow White', 'Off White', 'Ivory', 'Cream', 'Pure White'
            ],
            'Silver' => [
                'Silver Muda', 'Silver Tua', 'Metallic Silver', 'Grey Silver'
            ],
            'Pink' => [
                'Baby Pink', 'Fuchsia', 'Rose', 'Dusty Pink', 'Salmon', 'Magenta Pink'
            ],
        ];

        // Random parent color
        $color = Colors::inRandomOrder()->first();

        // Pick sub-color from mapping (fallback to safe value)
        $subName = $this->faker->randomElement(
            $colorMap[$color->name] ?? ['Undefined Sub Color']
        );

        return [
            'name' => $subName,
            'color_id' => $color->id,
        ];
    }
}

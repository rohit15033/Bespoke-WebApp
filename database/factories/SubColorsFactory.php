<?php

namespace Database\Factories;

use App\Models\SubColors;
use App\Models\Colors;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubColors>
 */
class SubColorsFactory extends Factory
{
    protected $model = SubColors::class;

    public function definition(): array
    {
        // Define realistic fashion-related sub-colors for each main color name
        $colorMap = [
            'Biru' => [
                'Biru Muda', 'Biru Langit', 'Biru Navy', 'Biru Royal', 'Biru Toska'
            ],
            'Merah' => [
                'Merah Marun', 'Merah Bata', 'Merah Hati', 'Merah Terang', 'Merah Anggur'
            ],
            'Gold' => [
                'Emas Klasik', 'Emas Muda', 'Champagne Gold', 'Rose Gold', 'Golden Bronze'
            ],
            'Cokelat' => [
                'Cokelat Susu', 'Cokelat Tua', 'Cokelat Kopi', 'Beige', 'Mocha'
            ],
            'Hijau' => [
                'Hijau Emerald', 'Hijau Sage', 'Hijau Mint', 'Hijau Olive', 'Hijau Toska'
            ],
            'Ungu' => [
                'Lavender', 'Ungu Tua', 'Lilac', 'Plum', 'Mauve'
            ],
            'Hitam' => [
                'Hitam Pekat', 'Abu Gelap', 'Charcoal', 'Graphite', 'Midnight Black'
            ],
            'Putih' => [
                'Putih Gading', 'Putih Mutiara', 'Ivory', 'Off White', 'Putih Salju'
            ],
            'Silver' => [
                'Abu Muda', 'Abu Silver', 'Platinum', 'Steel Grey', 'Pewter'
            ],
            'Pink' => [
                'Dusty Pink', 'Blush', 'Fuchsia', 'Rose', 'Soft Pink'
            ],
        ];

        // Pick a random color from the Colors table
        $color = Colors::inRandomOrder()->first();

        // Choose a sub-color based on the parent color name (fallback to random if missing)
        $subName = $this->faker->randomElement($colorMap[$color->name] ?? [$this->faker->colorName()]);

        return [
            'name' => $subName,
            'color_id' => $color->id,
        ];
    }
}

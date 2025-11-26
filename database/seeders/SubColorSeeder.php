<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Colors;
use App\Models\SubColors;

class SubColorSeeder extends Seeder
{
    public function run(): void
    {
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

        foreach ($colorMap as $colorName => $subs) {

            $color = Colors::where('name', $colorName)->first();

            if (!$color) {
                continue;
            }

            foreach ($subs as $sub) {
                SubColors::create([
                    'name' => $sub,
                    'color_id' => $color->id,
                ]);
            }
        }
    }
}

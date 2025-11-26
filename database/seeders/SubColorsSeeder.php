<?php

namespace Database\Seeders;

use App\Models\Colors;
use App\Models\SubColors;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sub colors to avoid duplicates
        SubColors::truncate();

        $colorMap = [
            'Biru' => [
                'Biru Donker / Navy', 
                'Biru Muda', 
                'Sky Blue', 
                'Royal Blue',
                'Turquoise', 
                'Tosca', 
                'Electric Blue'
            ],
            'Merah' => [
                'Maroon', 
                'Merah Cabe'
            ],
            'Gold' => [
                'Gold Tua', 
                'Gold Muda', 
                'Rose Gold', 
                'Champagne Gold', 
                'Yellow Gold'
            ],
            'Cokelat' => [
                'Cokelat Susu', 
                'Kopi', 
                'Nude Brown'
            ],
            'Hijau' => [
                'Army', 
                'Sage', 
                'Mint', 
                'Emerald', 
                'Tosca', 
                'Olive', 
                'Lumut', 
                'Botol'
            ],
            'Ungu' => [
                'Lavender', 
                'Lilac', 
                'Violet', 
                'Mauve', 
                'Magenta', 
                'Grape', 
                'Burgundy', 
                'Wine'
            ],
            'Hitam' => [
                'Jet Black', 
                'Metallic Black'
            ],
            'Putih' => [
                'Snow White', 
                'Off White', 
                'Ivory', 
                'Cream', 
                'Pure White'
            ],
            'Silver' => [
                'Silver Muda', 
                'Silver Tua', 
                'Metallic Silver', 
                'Grey Silver'
            ],
            'Pink' => [
                'Baby Pink', 
                'Fuchsia', 
                'Rose', 
                'Dusty Pink', 
                'Salmon', 
                'Magenta Pink'
            ],
        ];

        foreach ($colorMap as $colorName => $subColors) {
            // Find the parent color
            $color = Colors::where('name', $colorName)->first();
            
            if ($color) {
                foreach ($subColors as $subColorName) {
                    SubColors::create([
                        'name' => $subColorName,
                        'color_id' => $color->id,
                    ]);
                }
                
                $this->command->info("Created " . count($subColors) . " sub-colors for {$colorName}");
            } else {
                $this->command->error("Color '{$colorName}' not found in database!");
            }
        }

        $this->command->info('Sub colors seeded successfully!');
    }
}
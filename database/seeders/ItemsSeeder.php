<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Items;
use App\Models\ItemsImagesUrls;
use App\Models\SubColors;

class ItemsSeeder extends Seeder
{
    /**
     * Seed the items table with essential data only.
     */
    public function run(): void
    {
        // Check if subcolors exist first
        $subcolorIds = SubColors::query()->pluck('id')->all();
        if (empty($subcolorIds)) {
            $this->command->info('No subcolors found. Please run SubColors seeder first.');
            return;
        }

        $this->command->info('Items seeder completed. No sample items created.');
        
        // If you want to create a few essential items for testing, uncomment below:
        /*
        $essentialItems = [
            [
                'name' => 'Sample Kebaya Traditional',
                'code' => 'KBY-TRAD-001',
                'type' => 'kebaya',
                'production_month' => 6,
                'production_year' => 2024,
                'subcolor_id' => $subcolorIds[array_rand($subcolorIds)],
            ],
            [
                'name' => 'Sample Beskap Formal',
                'code' => 'BSK-FRM-001', 
                'type' => 'beskap',
                'production_month' => 3,
                'production_year' => 2024,
                'subcolor_id' => $subcolorIds[array_rand($subcolorIds)],
            ],
        ];

        foreach ($essentialItems as $itemData) {
            $item = Items::firstOrCreate(
                ['code' => $itemData['code']],
                $itemData
            );

            // Add sample image if item was created
            if ($item->wasRecentlyCreated) {
                ItemsImagesUrls::firstOrCreate([
                    'item_id' => $item->id,
                    'image_url' => '/images/placeholder-item.jpg'
                ]);
            }
        }
        */
    }
}
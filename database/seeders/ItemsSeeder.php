<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Items;
use App\Models\ItemsImagesUrls;
use App\Models\SubColors;

class ItemsSeeder extends Seeder
{
    /**
     * Seed the items table with a large dataset and related images.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $allowedTypes = [
            'kebaya',
            'beskap',
            'celana',
            'selop',
            'bustier',
            'manset',
            'hijab',
            'veil',
            'ekor',
            'vest',
            'dasi',
            'kemeja',
            'headwear',
            'accessories'
        ];

        $subcolorIds = SubColors::query()->pluck('id')->all();
        if (empty($subcolorIds)) {
            return; // Preconditions not met; upstream seeder should create subcolors
        }

        $now = now();
        $itemsToInsert = [];

        $targetCount = 500; // "huge" dataset
        for ($i = 0; $i < $targetCount; $i++) {
            $name = ucfirst($faker->words(rand(2, 4), true));
            $code = strtoupper(Str::slug($name, '')) . '-' . strtoupper(Str::random(6));
            $type = $allowedTypes[array_rand($allowedTypes)];
            $month = $faker->optional(0.8)->numberBetween(1, 12);
            $year = $faker->optional(0.9)->numberBetween(2015, (int) date('Y'));
            $subcolorId = $subcolorIds[array_rand($subcolorIds)];

            $itemsToInsert[] = [
                'name' => $name,
                'code' => $code,
                'type' => $type,
                'production_month' => $month,
                'production_year' => $year,
                'subcolor_id' => $subcolorId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Bulk insert for performance
        foreach (array_chunk($itemsToInsert, 500) as $chunk) {
            DB::table('items')->insert($chunk);
        }

        // Fetch inserted items ids to create images
        $itemIds = Items::query()->orderByDesc('id')->limit($targetCount)->pluck('id')->all();

        $imagesToInsert = [];
        foreach ($itemIds as $itemId) {
            $imagesCount = rand(1, 4);
            for ($k = 0; $k < $imagesCount; $k++) {
                $imagesToInsert[] = [
                    'item_id' => $itemId,
                    'image_url' => $faker->imageUrl(800, 1200, 'fashion', true),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($imagesToInsert, 1000) as $chunk) {
            DB::table('items_images_urls')->insert($chunk);
        }
    }
}




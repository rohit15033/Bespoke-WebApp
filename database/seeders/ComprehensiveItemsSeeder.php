<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Items;
use App\Models\Colors;
use App\Models\SubColors;
use App\Models\Occasions;

class ComprehensiveItemsSeeder extends Seeder
{
    /**
     * Seed the items table with comprehensive data using the specified code format.
     * Format: items-color-types-subtypes-number
     */
    public function run(): void
    {
        // Item type codes mapping
        $itemTypeCodes = [
            'kebaya' => 'KBY',
            'beskap' => 'BSP',
            'celana' => 'CLNA',
            'selop' => 'SLP',
            'bustier' => 'BST',
            'manset' => 'MST',
            'hijab' => 'HJB',
            'veil' => 'VIL',
            'ekor' => 'EKR',
            'vest' => 'VST',
            'dasi' => 'DSI',
            'kemeja' => 'KMJA',
            'headwear' => 'HW',
            'accessories' => 'AKSR',
        ];

        // Subtype codes for headwear
        $headwearSubtypes = [
            'Bowtie' => 'BWT',
            'Regular' => 'RGL',
            'Blangkon' => 'BLG',
            'Peci' => 'PCI',
            'Tanjak' => 'TNJK',
        ];

        // Subtype codes for kebaya patterns
        $kebayaSubtypes = [
            'Jawa' => 'JAWA',
            'Sunda' => 'SNDA',
            'Polos' => 'PLS',
            'Batik' => 'BTK',
            'Motif' => 'MTF',
        ];

        // Color codes
        $colorCodes = [
            'Red' => 'MRH',
            'Blue' => 'BLU',
            'Gold' => 'GLD',
            'Brown' => 'BRN',
            'Green' => 'GRN',
            'Purple' => 'PRP',
            'Black' => 'BLK',
            'White' => 'WHT',
            'Silver' => 'SLV',
            'Pink' => 'PNK',
        ];

        // Clear existing items first to avoid duplicates
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('items')->truncate();
        DB::table('items_images_urls')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get existing data
        $colors = Colors::all()->keyBy('name');
        $subcolors = SubColors::with('color')->get();
        $occasions = Occasions::all();

        if ($colors->isEmpty() || $subcolors->isEmpty()) {
            $this->command->error('Colors and subcolors must be seeded first!');
            return;
        }

        $itemsToInsert = [];
        $itemCounter = 1;

        foreach ($itemTypeCodes as $itemType => $itemCode) {
            // Generate 8-12 items per type for better variety
            $itemCount = rand(8, 12);

            for ($i = 0; $i < $itemCount; $i++) {
                // Select random color and subcolor
                $randomSubcolor = $subcolors->random();
                $colorName = $randomSubcolor->color->name;
                $subcolorName = $randomSubcolor->name;

                // Get color code
                $colorCode = $colorCodes[$colorName] ?? 'UNK';

                // Generate subtype based on item type
                $subtypeCode = '';
                $subtypeName = '';
                $subtypeNumber = '001';

                if ($itemType === 'headwear') {
                    $subtypeName = array_rand($headwearSubtypes);
                    $subtypeCode = $headwearSubtypes[$subtypeName];
                    $subtypeNumber = sprintf('%03d', rand(1, 5));
                } elseif ($itemType === 'kebaya') {
                    $subtypeName = array_rand($kebayaSubtypes);
                    $subtypeCode = $kebayaSubtypes[$subtypeName];
                    $subtypeNumber = sprintf('%03d', rand(1, 10));
                } else {
                    // For other items, use a generic subtype
                    $subtypeCode = 'REG';
                    $subtypeName = 'Regular';
                    $subtypeNumber = sprintf('%03d', rand(1, 3));
                }

                // Generate item name
                $itemName = $this->generateItemName($itemType, $subtypeName, $colorName, $subcolorName);

                // Generate code: items-color-types-subtypes-number
                $code = sprintf(
                    '%s-%s-%s-%s-%03d',
                    $itemCode,
                    $colorCode,
                    $subtypeCode,
                    $subtypeNumber,
                    $itemCounter
                );

                $itemsToInsert[] = [
                    'name' => $itemName,
                    'code' => $code,
                    'type' => $itemType,
                    'production_month' => rand(1, 12),
                    'production_year' => rand(2020, date('Y')),
                    'subcolor_id' => $randomSubcolor->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $itemCounter++;
            }
        }

        // Bulk insert items
        DB::table('items')->insert($itemsToInsert);

        // Create images for the items
        $this->createItemImages();

        $this->command->info('Created ' . count($itemsToInsert) . ' items with comprehensive codes and images.');
    }

    /**
     * Generate realistic item names
     */
    private function generateItemName($itemType, $subtype, $color, $subcolor): string
    {
        $names = [
            'kebaya' => [
                'Elegant Kebaya',
                'Traditional Kebaya',
                'Modern Kebaya',
                'Formal Kebaya',
                'Casual Kebaya',
                'Designer Kebaya',
                'Premium Kebaya',
                'Classic Kebaya',
            ],
            'beskap' => [
                'Formal Beskap',
                'Traditional Beskap',
                'Modern Beskap',
                'Elegant Beskap',
                'Premium Beskap',
                'Classic Beskap',
                'Designer Beskap',
            ],
            'celana' => [
                'Comfortable Celana',
                'Formal Celana',
                'Casual Celana',
                'Designer Celana',
                'Premium Celana',
                'Classic Celana',
            ],
            'selop' => [
                'Leather Selop',
                'Traditional Selop',
                'Modern Selop',
                'Comfortable Selop',
                'Premium Selop',
                'Classic Selop',
            ],
            'bustier' => [
                'Elegant Bustier',
                'Formal Bustier',
                'Modern Bustier',
                'Designer Bustier',
                'Premium Bustier',
                'Classic Bustier',
            ],
            'manset' => [
                'Comfortable Manset',
                'Formal Manset',
                'Traditional Manset',
                'Modern Manset',
                'Premium Manset',
                'Classic Manset',
            ],
            'hijab' => [
                'Elegant Hijab',
                'Modern Hijab',
                'Traditional Hijab',
                'Premium Hijab',
                'Designer Hijab',
                'Classic Hijab',
            ],
            'veil' => [
                'Bridal Veil',
                'Elegant Veil',
                'Traditional Veil',
                'Modern Veil',
                'Premium Veil',
                'Classic Veil',
            ],
            'ekor' => [
                'Formal Ekor',
                'Traditional Ekor',
                'Modern Ekor',
                'Elegant Ekor',
                'Premium Ekor',
                'Classic Ekor',
            ],
            'vest' => [
                'Formal Vest',
                'Modern Vest',
                'Traditional Vest',
                'Elegant Vest',
                'Premium Vest',
                'Classic Vest',
            ],
            'dasi' => [
                'Silk Dasi',
                'Formal Dasi',
                'Traditional Dasi',
                'Modern Dasi',
                'Premium Dasi',
                'Classic Dasi',
            ],
            'kemeja' => [
                'Formal Kemeja',
                'Casual Kemeja',
                'Modern Kemeja',
                'Traditional Kemeja',
                'Premium Kemeja',
                'Classic Kemeja',
            ],
            'headwear' => [
                'Traditional Headwear',
                'Formal Headwear',
                'Modern Headwear',
                'Elegant Headwear',
                'Premium Headwear',
                'Classic Headwear',
            ],
            'accessories' => [
                'Elegant Accessories',
                'Modern Accessories',
                'Traditional Accessories',
                'Premium Accessories',
                'Designer Accessories',
                'Classic Accessories',
            ],
        ];

        $itemNames = $names[$itemType] ?? ['Standard Item'];
        $baseName = $itemNames[array_rand($itemNames)];

        return $baseName . ' ' . $subtype . ' ' . $color . ' ' . $subcolor;
    }

    /**
     * Create images for all items
     */
    private function createItemImages(): void
    {
        $faker = \Faker\Factory::create();
        $items = Items::all();
        $imagesToInsert = [];

        foreach ($items as $item) {
            // Generate 2-5 images per item
            $imageCount = rand(2, 5);

            for ($i = 0; $i < $imageCount; $i++) {
                $imagesToInsert[] = [
                    'item_id' => $item->id,
                    'image_url' => $faker->imageUrl(800, 1200, 'fashion', true, 'Faker', true),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Bulk insert images
        foreach (array_chunk($imagesToInsert, 1000) as $chunk) {
            DB::table('items_images_urls')->insert($chunk);
        }

        $this->command->info('Created ' . count($imagesToInsert) . ' item images.');
    }
}

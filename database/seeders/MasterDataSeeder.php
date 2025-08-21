<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemBlueprint;
use App\Models\PackageBlueprint;
use App\Models\SetBlueprint;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample items
        $items = [
            [
                'sku' => 'KBY-RD-M',
                'name' => 'Traditional Red Kebaya',
                'type' => 'Kebaya',
                'color' => 'Red',
                'size' => 'M',
                'image_url' => 'https://example.com/images/kebaya-red-m.jpg',
            ],
            [
                'sku' => 'KBY-BL-L',
                'name' => 'Elegant Blue Kebaya',
                'type' => 'Kebaya',
                'color' => 'Blue',
                'size' => 'L',
                'image_url' => 'https://example.com/images/kebaya-blue-l.jpg',
            ],
            [
                'sku' => 'BSK-BK-M',
                'name' => 'Classic Black Beskap',
                'type' => 'Beskap',
                'color' => 'Black',
                'size' => 'M',
                'image_url' => 'https://example.com/images/beskap-black-m.jpg',
            ],
            [
                'sku' => 'BSK-GD-L',
                'name' => 'Golden Beskap',
                'type' => 'Beskap',
                'color' => 'Gold',
                'size' => 'L',
                'image_url' => 'https://example.com/images/beskap-gold-l.jpg',
            ],
            [
                'sku' => 'DRS-WT-S',
                'name' => 'White Wedding Dress',
                'type' => 'Dress',
                'color' => 'White',
                'size' => 'S',
                'image_url' => 'https://example.com/images/dress-white-s.jpg',
            ],
            [
                'sku' => 'KBY-WD-01',
                'name' => 'Kebaya Encim Modern',
                'type' => 'Kebaya',
                'color' => 'White',
                'size' => 'M',
                'image_url' => 'https://placehold.co/400x600/f0f0f0/333?text=Kebaya+Encim',
            ],
            [
                'sku' => 'KBY-RD-02',
                'name' => 'Kebaya Kutubaru',
                'type' => 'Kebaya',
                'color' => 'Red',
                'size' => 'L',
                'image_url' => 'https://placehold.co/400x600/e0e0e0/333?text=Kebaya+Kutubaru',
            ],
            [
                'sku' => 'SRG-BT-01',
                'name' => 'Sarung Batik',
                'type' => 'Sarung',
                'color' => 'Brown',
                'size' => 'All Size',
                'image_url' => 'https://placehold.co/400x600/d0d0d0/333?text=Sarung+Batik',
            ],
            [
                'sku' => 'SHS-MN-BLK',
                'name' => 'Men\'s Formal Shoes',
                'type' => 'Shoes',
                'color' => 'Black',
                'size' => '42',
                'image_url' => 'https://placehold.co/400x600/c0c0c0/333?text=Men+Shoes',
            ],
            [
                'sku' => 'SHS-WM-GLD',
                'name' => 'Women\'s Heels',
                'type' => 'Shoes',
                'color' => 'Gold',
                'size' => '38',
                'image_url' => 'https://placehold.co/400x600/b0b0b0/333?text=Women+Heels',
            ],
            [
                'sku' => 'BSK-MN-CRM',
                'name' => 'Beskap Sunda',
                'type' => 'Beskap',
                'color' => 'Cream',
                'size' => 'L',
                'image_url' => 'https://placehold.co/400x600/a0a0a0/333?text=Beskap',
            ],
            [
                'sku' => 'BLG-MN-BLK',
                'name' => 'Blangkon Solo',
                'type' => 'Blangkon',
                'color' => 'Black Batik',
                'size' => 'M',
                'image_url' => 'https://placehold.co/400x600/909090/333?text=Blangkon',
            ],
            [
                'sku' => 'CLN-MN-BLK',
                'name' => 'Celana Panjang Hitam',
                'type' => 'Celana',
                'color' => 'Black',
                'size' => 'L',
                'image_url' => 'https://placehold.co/400x600/a5a5a5/333?text=Celana',
            ],
            [
                'sku' => 'ACC-MN-GLD',
                'name' => 'Keris & Bros Emas',
                'type' => 'Accessories',
                'color' => 'Gold',
                'size' => 'N/A',
                'image_url' => 'https://placehold.co/400x600/959595/333?text=Aksesoris',
            ],
        ];

        foreach ($items as $itemData) {
            Item::create($itemData);
        }

        // Create sample package blueprints
        $packageBlueprints = [
            [
                'name' => 'Asmara Dana Wedding Package',
                'default_price' => 2500000,
                'default_discount' => 0,
                'note' => 'Asmara Dana',
            ],
            [
                'name' => 'Solo Traditional Set',
                'default_price' => 2200000,
                'default_discount' => 5,
                'note' => 'Traditional Solo-inspired set',
            ],
            [
                'name' => 'Modern Couple Package',
                'default_price' => 2700000,
                'default_discount' => 10,
                'note' => 'A sliver of western culture',
            ],
            [
                'name' => 'Classic Javanese Set',
                'default_price' => 2100000,
                'default_discount' => 0,
                'note' => 'Classic Javanese silk',
            ],
            [
                'name' => 'Elegant Wedding Set',
                'default_price' => 3000000,
                'default_discount' => 15,
                'note' => 'Only for the best',
            ],
            [
                'name' => 'Simple Traditional Set',
                'default_price' => 1800000,
                'default_discount' => 0,
                'note' => 'All Indonesian culture in one',
            ],
        ];

        foreach ($packageBlueprints as $packageData) {
            PackageBlueprint::create($packageData);
        }

        // Create sample set blueprints for each package
        $setBlueprints = [
            // For Asmara Dana Wedding Package (ID: 1)
            ['package_blueprint_id' => 1, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 1, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Solo Traditional Set (ID: 2)
            ['package_blueprint_id' => 2, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 2, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Modern Couple Package (ID: 3)
            ['package_blueprint_id' => 3, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 3, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Classic Javanese Set (ID: 4)
            ['package_blueprint_id' => 4, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 4, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Elegant Wedding Set (ID: 5)
            ['package_blueprint_id' => 5, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 5, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Simple Traditional Set (ID: 6)
            ['package_blueprint_id' => 6, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 6, 'name' => "Woman's Set", 'sort_order' => 1],
        ];

        foreach ($setBlueprints as $setData) {
            SetBlueprint::create($setData);
        }

        // Create sample item blueprints for each set
        $itemBlueprints = [
            // Asmara Dana - Man's Set (Set ID: 1)
            ['set_blueprint_id' => 1, 'item_type' => 'Beskap', 'sort_order' => 0],
            ['set_blueprint_id' => 1, 'item_type' => 'Celana', 'sort_order' => 1],
            ['set_blueprint_id' => 1, 'item_type' => 'Blangkon', 'sort_order' => 2],
            ['set_blueprint_id' => 1, 'item_type' => 'Accessories', 'sort_order' => 3],
            // Asmara Dana - Woman's Set (Set ID: 2)
            ['set_blueprint_id' => 2, 'item_type' => 'Kebaya', 'sort_order' => 0],
            ['set_blueprint_id' => 2, 'item_type' => 'Sarung', 'sort_order' => 1],
            ['set_blueprint_id' => 2, 'item_type' => 'Shoes', 'sort_order' => 2],
            // Solo Traditional - Man's Set (Set ID: 3)
            ['set_blueprint_id' => 3, 'item_type' => 'Beskap', 'sort_order' => 0],
            ['set_blueprint_id' => 3, 'item_type' => 'Celana', 'sort_order' => 1],
            ['set_blueprint_id' => 3, 'item_type' => 'Blangkon', 'sort_order' => 2],
            // Solo Traditional - Woman's Set (Set ID: 4)
            ['set_blueprint_id' => 4, 'item_type' => 'Kebaya', 'sort_order' => 0],
            ['set_blueprint_id' => 4, 'item_type' => 'Sarung', 'sort_order' => 1],
            // Modern Couple - Man's Set (Set ID: 5)
            ['set_blueprint_id' => 5, 'item_type' => 'Beskap', 'sort_order' => 0],
            ['set_blueprint_id' => 5, 'item_type' => 'Celana', 'sort_order' => 1],
            ['set_blueprint_id' => 5, 'item_type' => 'Shoes', 'sort_order' => 2],
            // Modern Couple - Woman's Set (Set ID: 6)
            ['set_blueprint_id' => 6, 'item_type' => 'Kebaya', 'sort_order' => 0],
            ['set_blueprint_id' => 6, 'item_type' => 'Shoes', 'sort_order' => 1],
            // Classic Javanese - Man's Set (Set ID: 7)
            ['set_blueprint_id' => 7, 'item_type' => 'Beskap', 'sort_order' => 0],
            ['set_blueprint_id' => 7, 'item_type' => 'Blangkon', 'sort_order' => 1],
            ['set_blueprint_id' => 7, 'item_type' => 'Accessories', 'sort_order' => 2],
            // Classic Javanese - Woman's Set (Set ID: 8)
            ['set_blueprint_id' => 8, 'item_type' => 'Kebaya', 'sort_order' => 0],
            ['set_blueprint_id' => 8, 'item_type' => 'Sarung', 'sort_order' => 1],
            // Elegant Wedding - Man's Set (Set ID: 9)
            ['set_blueprint_id' => 9, 'item_type' => 'Beskap', 'sort_order' => 0],
            ['set_blueprint_id' => 9, 'item_type' => 'Celana', 'sort_order' => 1],
            ['set_blueprint_id' => 9, 'item_type' => 'Shoes', 'sort_order' => 2],
            ['set_blueprint_id' => 9, 'item_type' => 'Accessories', 'sort_order' => 3],
            // Elegant Wedding - Woman's Set (Set ID: 10)
            ['set_blueprint_id' => 10, 'item_type' => 'Kebaya', 'sort_order' => 0],
            ['set_blueprint_id' => 10, 'item_type' => 'Sarung', 'sort_order' => 1],
            ['set_blueprint_id' => 10, 'item_type' => 'Shoes', 'sort_order' => 2],
            // Simple Traditional - Man's Set (Set ID: 11)
            ['set_blueprint_id' => 11, 'item_type' => 'Beskap', 'sort_order' => 0],
            ['set_blueprint_id' => 11, 'item_type' => 'Celana', 'sort_order' => 1],
            // Simple Traditional - Woman's Set (Set ID: 12)
            ['set_blueprint_id' => 12, 'item_type' => 'Kebaya', 'sort_order' => 0],
        ];

        foreach ($itemBlueprints as $itemData) {
            ItemBlueprint::create($itemData);
        }
    }
}

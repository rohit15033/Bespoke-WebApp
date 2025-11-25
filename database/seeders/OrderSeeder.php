<?php

namespace Database\Seeders;

use App\Models\ItemBlueprint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ItemType;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use App\Models\OrderSet;
use App\Models\PackageBlueprint;
use App\Models\SetBlueprint;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // Create a sample order
        // $order = Order::create([
        //     'order_number' => 'ORD-2025-001',
        //     'status' => 'confirmed',
        //     'customer_name' => 'John Doe',
        //     'customer_address' => '123 Main Street, Jakarta, Indonesia',
        //     'customer_phone_number' => '+62-812-3456-7890',
        //     'event_place' => 'Grand Ballroom Hotel Indonesia',
        //     'event_date' => '2025-12-25',
        //     'total_price' => 3500000,
        //     'total_discount' => 350000,
        //     'final_price' => 3150000,
        // ]);

        // // Create a package for this order
        // $package = OrderPackage::create([
        //     'name' => 'Asmara Dana Wedding Package',
        //     'price' => 2500000,
        //     'discount' => 250000,
        //     'note' => 'Complete wedding package for the bride and groom',
        // ]);

        // // Link the package to the order
        // OrderProduct::create([
        //     'order_id' => $order->id,
        //     'product_id' => $package->id,
        //     'product_type' => 'package',
        //     'sort_order' => 1,
        // ]);

        // // Create sets for the package
        // $womanSet = OrderSet::create([
        //     'order_package_id' => $package->id,
        //     'name' => "Woman's Set",
        // ]);

        // $manSet = OrderSet::create([
        //     'order_package_id' => $package->id,
        //     'name' => "Man's Set",
        // ]);

        // // Create items for the bride's set
        // $brideKebaya = OrderItem::create([
        //     'order_set_id' => $womanSet->id,
        //     'item_id' => 1,
        //     'note' => 'Traditional red kebaya for the bride',
        //     'status' => 'active',
        //     'is_additional' => false,
        //     'is_custom' => false,
        //     'rental_status' => 'rent',
        //     'description' => 'Bride',
        //     'price' => null,
        //     'discount' => null,
        // ]);

        // // Create order item types for bride kebaya
        // $brideKebaya->orderItemTypes()->create([
        //     'name' => 'Kebaya',
        //     'sort_order' => 0,
        // ]);

        // $brideSarung = OrderItem::create([
        //     'order_set_id' => $womanSet->id,
        //     'item_id' => 1,
        //     'note' => 'Rok Batik',
        //     'status' => 'active',
        //     'is_additional' => false,
        //     'is_custom' => false,
        //     'rental_status' => 'rent',
        //     'description' => 'Bride',
        //     'price' => null,
        //     'discount' => null,
        // ]);

        // // Create order item types for bride sarung
        // $brideSarung->orderItemTypes()->create([
        //     'name' => 'Rok',
        //     'sort_order' => 0,
        // ]);

        // $brideShoes = OrderItem::create([
        //     'order_set_id' => $womanSet->id,
        //     'item_id' => 1,
        //     'note' => 'Add colorful beads',
        //     'status' => 'active',
        //     'is_additional' => false,
        //     'is_custom' => false,
        //     'rental_status' => 'rent',
        //     'description' => 'Bride',
        //     'price' => null,
        //     'discount' => null,
        // ]);

        // // Create order item types for bride shoes
        // $brideShoes->orderItemTypes()->create([
        //     'name' => 'Selop Wanita',
        //     'sort_order' => 0,
        // ]);

        // // Create items for the groom's set
        // $groomBeskap = OrderItem::create([
        //     'order_set_id' => $manSet->id,
        //     'item_id' => 1,
        //     'note' => 'Classic red beskap for the groom',
        //     'status' => 'active',
        //     'is_additional' => false,
        //     'is_custom' => false,
        //     'rental_status' => 'purchase',
        //     'price' => null,
        //     'discount' => null,
        // ]);

        // // Create order item types for groom beskap
        // $groomBeskap->orderItemTypes()->create([
        //     'name' => 'Beskap',
        //     'sort_order' => 0,
        // ]);

        // $groomCelana = OrderItem::create([
        //     'order_set_id' => $manSet->id,
        //     'item_id' => null,
        //     'note' => 'No need',
        //     'status' => 'removed',
        //     'is_additional' => false,
        //     'is_custom' => false,
        //     'rental_status' => 'rent',
        //     'price' => null,
        //     'discount' => null,
        // ]);

        // // Create order item types for groom celana
        // $groomCelana->orderItemTypes()->create([
        //     'name' => 'Celana',
        //     'sort_order' => 0,
        // ]);

        // $groomBlangkon = OrderItem::create([
        //     'order_set_id' => $manSet->id,
        //     'item_id' => 1,
        //     'note' => 'Big size',
        //     'status' => 'active',
        //     'is_additional' => false,
        //     'is_custom' => false,
        //     'rental_status' => 'rent',
        //     'description' => 'Groom',
        //     'price' => null,
        //     'discount' => null,
        // ]);

        // // Create order item types for groom blangkon
        // $groomBlangkon->orderItemTypes()->create([
        //     'name' => 'Headwear',
        //     'sort_order' => 0,
        // ]);

        // $groomAccessories = OrderItem::create([
        //     'order_set_id' => $manSet->id,
        //     'item_id' => 1,
        //     'note' => 'Add beads',
        //     'status' => 'active',
        //     'is_additional' => false,
        //     'is_custom' => false,
        //     'rental_status' => 'rent',
        //     'description' => 'Groom',
        //     'price' => null,
        //     'discount' => null,
        // ]);

        // // Create order item types for groom accessories
        // $groomAccessories->orderItemTypes()->create([
        //     'name' => 'Aksesoris',
        //     'sort_order' => 0,
        // ]);

        // // Create a standalone item (not part of any package)
        // $standaloneItem = OrderItem::create([
        //     'order_set_id' => null,
        //     'item_id' => null,
        //     'note' => 'Custom jewelry set for the bride',
        //     'status' => 'active',
        //     'is_additional' => true,
        //     'is_custom' => true,
        //     'rental_status' => 'purchase',
        //     'price' => 500000,
        //     'discount' => 50000,
        //     'custom_name' => 'Diamond Jewelry Set',
        //     'custom_type' => 'Accessory',
        //     'custom_details' => 'Custom made diamond jewelry set including necklace, earrings, and bracelet',
        // ]);

        // // Create order item types for standalone item
        // $standaloneItem->orderItemTypes()->create([
        //     'name' => 'Aksesoris',
        //     'sort_order' => 0,
        // ]);

        // // Link the standalone item to the order
        // OrderProduct::create([
        //     'order_id' => $order->id,
        //     'product_id' => $standaloneItem->id,
        //     'product_type' => 'item',
        //     'sort_order' => 2,
        // ]);

    //     // Create another order with different structure
    //     $order2 = Order::create([
    //         'order_number' => 'ORD-2025-002',
    //         'status' => 'draft',
    //         'customer_name' => 'Jane Smith',
    //         'customer_address' => '456 Oak Avenue, Bandung, Indonesia',
    //         'customer_phone_number' => '+62-813-9876-5432',
    //         'event_place' => 'Bandung Convention Center',
    //         'event_date' => '2025-11-15',
    //         'total_price' => 1200000,
    //         'total_discount' => 60000,
    //         'final_price' => 1140000,
    //     ]);

    //     // Create a simple package for the second order
    //     $package2 = OrderPackage::create([
    //         'name' => 'Simple Traditional',
    //         'price' => 1200000,
    //         'discount' => 60000,
    //         'note' => 'Simple traditional ceremony package',
    //     ]);

    //     // Link the package to the second order
    //     OrderProduct::create([
    //         'order_id' => $order2->id,
    //         'product_id' => $package2->id,
    //         'product_type' => 'package',
    //         'sort_order' => 1,
    //     ]);

    //     // Create a single set for this package
    //     $womanSet = OrderSet::create([
    //         'order_package_id' => $package2->id,
    //         'name' => "Woman's Set",
    //     ]);

    //     $manSet = OrderSet::create([
    //         'order_package_id' => $package2->id,
    //         'name' => "Man's Set",
    //     ]);

    //     // Create items for the simple set
    //     $simpleKebaya = OrderItem::create([
    //         'order_set_id' => $womanSet->id,
    //         'item_id' => 1,
    //         'note' => 'Elegant blue kebaya',
    //         'status' => 'active',
    //         'is_additional' => false,
    //         'is_custom' => false,
    //         'rental_status' => 'rent',
    //         'price' => null,
    //         'discount' => null,
    //         'sort_order' => 0,
    //     ]);

    //     // Create order item types for simple kebaya
    //     $simpleKebaya->orderItemTypes()->create([
    //         'name' => 'Kebaya',
    //         'sort_order' => 0,
    //     ]);

    //     $simpleShoes = OrderItem::create([
    //         'order_set_id' => $womanSet->id,
    //         'item_id' => 1,
    //         'note' => 'Clean and polish it',
    //         'status' => 'active',
    //         'is_additional' => true,
    //         'is_custom' => false,
    //         'rental_status' => 'rent',
    //         'price' => null,
    //         'discount' => null,
    //         'sort_order' => 1,
    //     ]);

    //     // Create order item types for simple shoes
    //     $simpleShoes->orderItemTypes()->create([
    //         'name' => 'Selop Wanita',
    //         'sort_order' => 0,
    //     ]);

    //     $simpleBeskap = OrderItem::create([
    //         'order_set_id' => $manSet->id,
    //         'item_id' => 1,
    //         'note' => 'White beskap',
    //         'status' => 'active',
    //         'is_additional' => false,
    //         'is_custom' => false,
    //         'rental_status' => 'rent',
    //         'price' => null,
    //         'discount' => null,
    //         'sort_order' => 0,
    //     ]);

    //     // Create order item types for simple beskap
    //     $simpleBeskap->orderItemTypes()->create([
    //         'name' => 'Beskap',
    //         'sort_order' => 0,
    //     ]);

    //     $simpleCelana = OrderItem::create([
    //         'order_set_id' => $manSet->id,
    //         'item_id' => null,
    //         'note' => '-',
    //         'status' => 'removed',
    //         'is_additional' => false,
    //         'is_custom' => false,
    //         'rental_status' => 'rent',
    //         'price' => null,
    //         'discount' => null,
    //         'sort_order' => 1,
    //     ]);

    //     // Create order item types for simple celana
    //     $simpleCelana->orderItemTypes()->create([
    //         'name' => 'Celana',
    //         'sort_order' => 0,
    //     ]);

        $this->seedPackage();
    }

    private function seedPackage(): void
    {
        $itemTypes = ['kebaya', 'beskap', 'celana', 'selop', 'bustier', 'manset', 'hijab', 'veil', 'ekor', 'vest', 'dasi', 'kemeja', 'headwear', 'accessories', 'jas'];
        foreach ($itemTypes as $typeName) {
            ItemType::firstOrCreate(['name' => $typeName]);
        }

        // Create sample package blueprints
        $packageBlueprints = [
            [
                'name' => 'Berkat Silver',
                'default_price' => 10000000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Berkat Elegant',
                'default_price' => 17500000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Berkat Diamond',
                'default_price' => 25000000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Berkat Solitaire',
                'default_price' => 37500000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Parents Anggun',
                'default_price' => 15000000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Parents Glamour',
                'default_price' => 27500000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Graduation',
                'default_price' => 5000000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Engagement',
                'default_price' => 10000000,
                'default_discount' => 0,
                'note' => '',
            ]
        ];

        foreach ($packageBlueprints as $packageData) {
            PackageBlueprint::create($packageData);
        }

        // Create sample set blueprints for each package
        $setBlueprints = [
            // For Berkat Silver (ID: 1)
            ['package_blueprint_id' => 1, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 1, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Berkat Elegant (ID: 2)
            ['package_blueprint_id' => 2, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 2, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Berkat Diamond (ID: 3)
            ['package_blueprint_id' => 3, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 3, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Berkat Solitaire (ID: 4)
            ['package_blueprint_id' => 4, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 4, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Parents Anggun (ID: 5)
            ['package_blueprint_id' => 5, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 5, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Parents Glamour (ID: 6)
            ['package_blueprint_id' => 6, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 6, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Graduation (ID: 7)
            // ['package_blueprint_id' => 7, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 7, 'name' => "Woman's Set", 'sort_order' => 0],
            // For Engagement (ID: 8)
            ['package_blueprint_id' => 8, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 8, 'name' => "Woman's Set", 'sort_order' => 1],
        ];

        foreach ($setBlueprints as $setData) {
            SetBlueprint::create($setData);
        }

        // Create sample item blueprints for each set
        $itemBlueprints = [
            // Set 1 (Man's Set) - 5 items
            ['id' => 1, 'set_blueprint_id' => 1, 'item_type' => ['beskap'], 'sort_order' => 0],
            ['id' => 2, 'set_blueprint_id' => 1, 'item_type' => ['jas'], 'sort_order' => 1],
            ['id' => 3, 'set_blueprint_id' => 1, 'item_type' => ['celana'], 'sort_order' => 2],
            ['id' => 4, 'set_blueprint_id' => 1, 'item_type' => ['kain'], 'sort_order' => 3],
            ['id' => 5, 'set_blueprint_id' => 1, 'item_type' => ['headwear'], 'sort_order' => 4],

            // Set 2 (Woman's Set) - 5 items
            ['id' => 6, 'set_blueprint_id' => 2, 'item_type' => ['kebaya'], 'sort_order' => 0],
            ['id' => 7, 'set_blueprint_id' => 2, 'item_type' => ['gaun'], 'sort_order' => 1],
            ['id' => 8, 'set_blueprint_id' => 2, 'item_type' => ['bustier'], 'sort_order' => 2],
            ['id' => 9, 'set_blueprint_id' => 2, 'item_type' => ['rok'], 'sort_order' => 3],
            ['id' => 10, 'set_blueprint_id' => 2, 'item_type' => ['manset'], 'sort_order' => 4],

            // Set 3 (Berkat Elegant - Man's) - 6 items
            ['id' => 11, 'set_blueprint_id' => 3, 'item_type' => ['beskap'], 'sort_order' => 0],
            ['id' => 12, 'set_blueprint_id' => 3, 'item_type' => ['jas'], 'sort_order' => 1],
            ['id' => 13, 'set_blueprint_id' => 3, 'item_type' => ['celana'], 'sort_order' => 2],
            ['id' => 14, 'set_blueprint_id' => 3, 'item_type' => ['kain'], 'sort_order' => 3],
            ['id' => 15, 'set_blueprint_id' => 3, 'item_type' => ['headwear'], 'sort_order' => 4],
            ['id' => 16, 'set_blueprint_id' => 3, 'item_type' => ['selop pria'], 'sort_order' => 5],

            // Set 4 (Berkat Elegant - Woman's) - 6 items
            ['id' => 17, 'set_blueprint_id' => 4, 'item_type' => ['kebaya'], 'sort_order' => 0],
            ['id' => 18, 'set_blueprint_id' => 4, 'item_type' => ['gaun'], 'sort_order' => 1],
            ['id' => 19, 'set_blueprint_id' => 4, 'item_type' => ['bustier'], 'sort_order' => 2],
            ['id' => 20, 'set_blueprint_id' => 4, 'item_type' => ['rok'], 'sort_order' => 3],
            ['id' => 21, 'set_blueprint_id' => 4, 'item_type' => ['manset'], 'sort_order' => 4],
            ['id' => 22, 'set_blueprint_id' => 4, 'item_type' => ['selop wanita'], 'sort_order' => 5],

            // Set 5 (Berkat Diamond - Man's) - 7 items
            ['id' => 23, 'set_blueprint_id' => 5, 'item_type' => ['beskap'], 'sort_order' => 0],
            ['id' => 24, 'set_blueprint_id' => 5, 'item_type' => ['jas'], 'sort_order' => 1],
            ['id' => 25, 'set_blueprint_id' => 5, 'item_type' => ['celana'], 'sort_order' => 2],
            ['id' => 26, 'set_blueprint_id' => 5, 'item_type' => ['kain'], 'sort_order' => 3],
            ['id' => 27, 'set_blueprint_id' => 5, 'item_type' => ['headwear'], 'sort_order' => 4],
            ['id' => 28, 'set_blueprint_id' => 5, 'item_type' => ['selop pria'], 'sort_order' => 5],
            ['id' => 29, 'set_blueprint_id' => 5, 'item_type' => ['aksesories'], 'sort_order' => 6],

            // Set 6 (Berkat Diamond - Woman's) - 8 items
            ['id' => 30, 'set_blueprint_id' => 6, 'item_type' => ['kebaya'], 'sort_order' => 0],
            ['id' => 31, 'set_blueprint_id' => 6, 'item_type' => ['gaun'], 'sort_order' => 1],
            ['id' => 32, 'set_blueprint_id' => 6, 'item_type' => ['bustier'], 'sort_order' => 2],
            ['id' => 33, 'set_blueprint_id' => 6, 'item_type' => ['rok'], 'sort_order' => 3],
            ['id' => 34, 'set_blueprint_id' => 6, 'item_type' => ['manset'], 'sort_order' => 4],
            ['id' => 35, 'set_blueprint_id' => 6, 'item_type' => ['selop wanita'], 'sort_order' => 5],
            ['id' => 36, 'set_blueprint_id' => 6, 'item_type' => ['tail'], 'sort_order' => 6],
            ['id' => 37, 'set_blueprint_id' => 6, 'item_type' => ['aksesories'], 'sort_order' => 7],

            // Set 7 (Berkat Solitaire - Man's) - 7 items
            ['id' => 38, 'set_blueprint_id' => 7, 'item_type' => ['beskap'], 'sort_order' => 0],
            ['id' => 39, 'set_blueprint_id' => 7, 'item_type' => ['jas'], 'sort_order' => 1],
            ['id' => 40, 'set_blueprint_id' => 7, 'item_type' => ['celana'], 'sort_order' => 2],
            ['id' => 41, 'set_blueprint_id' => 7, 'item_type' => ['kain'], 'sort_order' => 3],
            ['id' => 42, 'set_blueprint_id' => 7, 'item_type' => ['headwear'], 'sort_order' => 4],
            ['id' => 43, 'set_blueprint_id' => 7, 'item_type' => ['aksesories'], 'sort_order' => 5],
            ['id' => 44, 'set_blueprint_id' => 7, 'item_type' => ['selop pria'], 'sort_order' => 6],

            // Set 8 (Berkat Solitaire - Woman's) - 8 items
            ['id' => 45, 'set_blueprint_id' => 8, 'item_type' => ['kebaya'], 'sort_order' => 0],
            ['id' => 46, 'set_blueprint_id' => 8, 'item_type' => ['gaun'], 'sort_order' => 1],
            ['id' => 47, 'set_blueprint_id' => 8, 'item_type' => ['bustier'], 'sort_order' => 2],
            ['id' => 48, 'set_blueprint_id' => 8, 'item_type' => ['rok'], 'sort_order' => 3],
            ['id' => 49, 'set_blueprint_id' => 8, 'item_type' => ['manset'], 'sort_order' => 4],
            ['id' => 50, 'set_blueprint_id' => 8, 'item_type' => ['selop wanita'], 'sort_order' => 5],
            ['id' => 51, 'set_blueprint_id' => 8, 'item_type' => ['tail'], 'sort_order' => 6],
            ['id' => 52, 'set_blueprint_id' => 8, 'item_type' => ['aksesories'], 'sort_order' => 7],

            // Set 9 (Parents Anggun - Mens) - 8 items
            ['id' => 53, 'set_blueprint_id' => 9, 'item_type' => ['beskap', 'jas'], 'sort_order' => 0],
            ['id' => 54, 'set_blueprint_id' => 9, 'item_type' => ['beskap', 'jas'], 'sort_order' => 1],
            ['id' => 55, 'set_blueprint_id' => 9, 'item_type' => ['celana', 'kain'], 'sort_order' => 2],
            ['id' => 56, 'set_blueprint_id' => 9, 'item_type' => ['celana', 'kain'], 'sort_order' => 3],
            ['id' => 57, 'set_blueprint_id' => 9, 'item_type' => ['headwear'], 'sort_order' => 4],
            ['id' => 58, 'set_blueprint_id' => 9, 'item_type' => ['headwear'], 'sort_order' => 5],
            ['id' => 59, 'set_blueprint_id' => 9, 'item_type' => ['selop pria'], 'sort_order' => 6],
            ['id' => 60, 'set_blueprint_id' => 9, 'item_type' => ['selop pria'], 'sort_order' => 7],

            // Set 10 (Parents Anggun - Womens) - 10 items
            ['id' => 61, 'set_blueprint_id' => 10, 'item_type' => ['kebaya', 'gaun'], 'sort_order' => 0],
            ['id' => 62, 'set_blueprint_id' => 10, 'item_type' => ['kebaya', 'gaun'], 'sort_order' => 1],
            ['id' => 63, 'set_blueprint_id' => 10, 'item_type' => ['bustier'], 'sort_order' => 2],
            ['id' => 64, 'set_blueprint_id' => 10, 'item_type' => ['bustier'], 'sort_order' => 3],
            ['id' => 65, 'set_blueprint_id' => 10, 'item_type' => ['rok'], 'sort_order' => 4],
            ['id' => 66, 'set_blueprint_id' => 10, 'item_type' => ['rok'], 'sort_order' => 5],
            ['id' => 67, 'set_blueprint_id' => 10, 'item_type' => ['manset'], 'sort_order' => 6],
            ['id' => 68, 'set_blueprint_id' => 10, 'item_type' => ['manset'], 'sort_order' => 7],
            ['id' => 69, 'set_blueprint_id' => 10, 'item_type' => ['selop wanita'], 'sort_order' => 8],
            ['id' => 70, 'set_blueprint_id' => 10, 'item_type' => ['selop wanita'], 'sort_order' => 9],

            // Set 11 (Parents Glamour - Mens) - 10 items
            ['id' => 71, 'set_blueprint_id' => 11, 'item_type' => ['beskap', 'jas'], 'sort_order' => 0],
            ['id' => 72, 'set_blueprint_id' => 11, 'item_type' => ['beskap', 'jas'], 'sort_order' => 1],
            ['id' => 73, 'set_blueprint_id' => 11, 'item_type' => ['celana'], 'sort_order' => 2],
            ['id' => 74, 'set_blueprint_id' => 11, 'item_type' => ['celana'], 'sort_order' => 3],
            ['id' => 75, 'set_blueprint_id' => 11, 'item_type' => ['kain'], 'sort_order' => 4],
            ['id' => 76, 'set_blueprint_id' => 11, 'item_type' => ['kain'], 'sort_order' => 5],
            ['id' => 77, 'set_blueprint_id' => 11, 'item_type' => ['headwear'], 'sort_order' => 6],
            ['id' => 78, 'set_blueprint_id' => 11, 'item_type' => ['headwear'], 'sort_order' => 7],
            ['id' => 79, 'set_blueprint_id' => 11, 'item_type' => ['selop pria'], 'sort_order' => 8],
            ['id' => 80, 'set_blueprint_id' => 11, 'item_type' => ['selop pria'], 'sort_order' => 9],

            // Set 12 (Parents Glamour - Womens) - 10 items
            ['id' => 81, 'set_blueprint_id' => 12, 'item_type' => ['kebaya', 'gaun'], 'sort_order' => 0],
            ['id' => 82, 'set_blueprint_id' => 12, 'item_type' => ['kebaya', 'gaun'], 'sort_order' => 1],
            ['id' => 83, 'set_blueprint_id' => 12, 'item_type' => ['bustier'], 'sort_order' => 2],
            ['id' => 84, 'set_blueprint_id' => 12, 'item_type' => ['bustier'], 'sort_order' => 3],
            ['id' => 85, 'set_blueprint_id' => 12, 'item_type' => ['rok'], 'sort_order' => 4],
            ['id' => 86, 'set_blueprint_id' => 12, 'item_type' => ['rok'], 'sort_order' => 5],
            ['id' => 87, 'set_blueprint_id' => 12, 'item_type' => ['manset'], 'sort_order' => 6],
            ['id' => 88, 'set_blueprint_id' => 12, 'item_type' => ['manset'], 'sort_order' => 7],
            ['id' => 89, 'set_blueprint_id' => 12, 'item_type' => ['selop wanita'], 'sort_order' => 8],
            ['id' => 90, 'set_blueprint_id' => 12, 'item_type' => ['selop wanita'], 'sort_order' => 9],

            // Set 13 (Graduation) - 6 items
            ['id' => 91, 'set_blueprint_id' => 13, 'item_type' => ['kebaya'], 'sort_order' => 0],
            ['id' => 92, 'set_blueprint_id' => 13, 'item_type' => ['bustier'], 'sort_order' => 1],
            ['id' => 93, 'set_blueprint_id' => 13, 'item_type' => ['rok'], 'sort_order' => 2],
            ['id' => 94, 'set_blueprint_id' => 13, 'item_type' => ['selop wanita'], 'sort_order' => 3],
            ['id' => 95, 'set_blueprint_id' => 13, 'item_type' => ['bustier'], 'sort_order' => 4],
            ['id' => 96, 'set_blueprint_id' => 13, 'item_type' => ['manset'], 'sort_order' => 5],

            // Set 14 (Engagement - Man's) - 1 item
            ['id' => 97, 'set_blueprint_id' => 14, 'item_type' => ['kemeja'], 'sort_order' => 0],

            // Set 15 (Engagement - Woman's) - 7 items
            ['id' => 98, 'set_blueprint_id' => 15, 'item_type' => ['kebaya'], 'sort_order' => 0],
            ['id' => 99, 'set_blueprint_id' => 15, 'item_type' => ['gaun'], 'sort_order' => 1],
            ['id' => 100, 'set_blueprint_id' => 15, 'item_type' => ['bustier'], 'sort_order' => 2],
            ['id' => 101, 'set_blueprint_id' => 15, 'item_type' => ['rok'], 'sort_order' => 3],
            ['id' => 102, 'set_blueprint_id' => 15, 'item_type' => ['selop wanita'], 'sort_order' => 4],
            ['id' => 103, 'set_blueprint_id' => 15, 'item_type' => ['accesories'], 'sort_order' => 5],
            ['id' => 104, 'set_blueprint_id' => 15, 'item_type' => ['manset'], 'sort_order' => 6],
        ];

        foreach ($itemBlueprints as $itemData) {
            $itemBlueprint = ItemBlueprint::create([
                'id' => $itemData['id'],
                'set_blueprint_id' => $itemData['set_blueprint_id'],
                'sort_order' => $itemData['sort_order'],
                // add other fields as needed
            ]);
            foreach ($itemData['item_type'] as $itemTypeName) {
                $itemType = ItemType::where('name', $itemTypeName)->first();
                if ($itemType) {
                    // Attach via pivot table with sort_order
                    $itemBlueprint->itemTypes()->attach($itemType->id, ['sort_order' => $itemData['sort_order']]);
                }
            }
        }
    }
}

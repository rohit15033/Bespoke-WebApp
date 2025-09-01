<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use App\Models\OrderSet;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample order
        $order = Order::create([
            'order_number' => 'ORD-2025-001',
            'status' => 'confirmed',
            'customer_name' => 'John Doe',
            'customer_address' => '123 Main Street, Jakarta, Indonesia',
            'customer_phone_number' => '+62-812-3456-7890',
            'event_place' => 'Grand Ballroom Hotel Indonesia',
            'event_date' => '2025-12-25',
            'total_price' => 3500000,
            'total_discount' => 350000,
            'final_price' => 3150000,
        ]);

        // Create a package for this order
        $package = OrderPackage::create([
            'name' => 'Asmara Dana Wedding Package',
            'price' => 2500000,
            'discount' => 250000,
            'note' => 'Complete wedding package for the bride and groom',
        ]);

        // Link the package to the order
        OrderProduct::create([
            'order_id' => $order->id,
            'product_id' => $package->id,
            'product_type' => 'package',
            'sort_order' => 1,
        ]);

        // Create sets for the package
        $womanSet = OrderSet::create([
            'order_package_id' => $package->id,
            'name' => "Woman's Set",
        ]);

        $manSet = OrderSet::create([
            'order_package_id' => $package->id,
            'name' => "Man's Set",
        ]);

        // Create items for the bride's set
        $brideKebaya = OrderItem::create([
            'order_set_id' => $womanSet->id,
            'item_sku' => 'KBY-RD-M',
            'note' => 'Traditional red kebaya for the bride',
            'status' => 'active',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Kebaya',
            'price' => null,
            'discount' => null,
        ]);

        $brideSarung = OrderItem::create([
            'order_set_id' => $womanSet->id,
            'item_sku' => 'SRG-BT-01',
            'note' => 'Sarung Batik Biru',
            'status' => 'active',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Sarung',
            'price' => null,
            'discount' => null,
        ]);

        $brideShoes = OrderItem::create([
            'order_set_id' => $womanSet->id,
            'item_sku' => 'SHS-WM-GLD',
            'note' => 'Add colorful beads',
            'status' => 'active',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Shoes',
            'price' => null,
            'discount' => null,
        ]);

        // Create items for the groom's set
        $groomBeskap = OrderItem::create([
            'order_set_id' => $manSet->id,
            'item_sku' => 'BSK-BK-M',
            'note' => 'Classic black beskap for the groom',
            'status' => 'active',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'purchase',
            'default_item_type' => 'Beskap',
            'price' => null,
            'discount' => null,
        ]);

        $groomCelana = OrderItem::create([
            'order_set_id' => $manSet->id,
            'item_sku' => null,
            'note' => 'No need',
            'status' => 'removed',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Celana',
            'price' => null,
            'discount' => null,
        ]);

        $groomBlangkon = OrderItem::create([
            'order_set_id' => $manSet->id,
            'item_sku' => 'BLG-MN-BLK',
            'note' => 'Big size',
            'status' => 'active',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Blangkon',
            'price' => null,
            'discount' => null,
        ]);

        $groomAccessories = OrderItem::create([
            'order_set_id' => $manSet->id,
            'item_sku' => 'ACC-MN-GLD',
            'note' => 'Add beads',
            'status' => 'active',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Accessories',
            'price' => null,
            'discount' => null,
        ]);

        // Create a standalone item (not part of any package)
        $standaloneItem = OrderItem::create([
            'order_set_id' => null,
            'item_sku' => null,
            'note' => 'Custom jewelry set for the bride',
            'status' => 'active',
            'is_additional' => true,
            'is_custom' => true,
            'rental_status' => 'purchase',
            'default_item_type' => null,
            'price' => 500000,
            'discount' => 50000,
            'custom_name' => 'Diamond Jewelry Set',
            'custom_type' => 'Accessory',
            'custom_details' => 'Custom made diamond jewelry set including necklace, earrings, and bracelet',
        ]);

        // Link the standalone item to the order
        OrderProduct::create([
            'order_id' => $order->id,
            'product_id' => $standaloneItem->id,
            'product_type' => 'item',
            'sort_order' => 2,
        ]);

        // Create another order with different structure
        $order2 = Order::create([
            'order_number' => 'ORD-2025-002',
            'status' => 'draft',
            'customer_name' => 'Jane Smith',
            'customer_address' => '456 Oak Avenue, Bandung, Indonesia',
            'customer_phone_number' => '+62-813-9876-5432',
            'event_place' => 'Bandung Convention Center',
            'event_date' => '2025-11-15',
            'total_price' => 1200000,
            'total_discount' => 60000,
            'final_price' => 1140000,
        ]);

        // Create a simple package for the second order
        $package2 = OrderPackage::create([
            'name' => 'Simple Traditional',
            'price' => 1200000,
            'discount' => 60000,
            'note' => 'Simple traditional ceremony package',
        ]);

        // Link the package to the second order
        OrderProduct::create([
            'order_id' => $order2->id,
            'product_id' => $package2->id,
            'product_type' => 'package',
            'sort_order' => 1,
        ]);

        // Create a single set for this package
        $womanSet = OrderSet::create([
            'order_package_id' => $package2->id,
            'name' => "Woman's Set",
        ]);

        $manSet = OrderSet::create([
            'order_package_id' => $package2->id,
            'name' => "Man's Set",
        ]);

        // Create items for the simple set
        OrderItem::create([
            'order_set_id' => $womanSet->id,
            'item_sku' => 'KBY-BL-L',
            'note' => 'Elegant blue kebaya',
            'status' => 'active',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Kebaya',
            'price' => null,
            'discount' => null,
            'sort_order' => 0,
        ]);

        OrderItem::create([
            'order_set_id' => $womanSet->id,
            'item_sku' => 'SHS-WM-GLD',
            'note' => 'Clean and polish it',
            'status' => 'active',
            'is_additional' => true,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Shoes',
            'price' => null,
            'discount' => null,
            'sort_order' => 1,
        ]);

        OrderItem::create([
            'order_set_id' => $manSet->id,
            'item_sku' => 'BSK-GD-L',
            'note' => 'Golden beskap',
            'status' => 'active',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Beskap',
            'price' => null,
            'discount' => null,
            'sort_order' => 0,
        ]);

        OrderItem::create([
            'order_set_id' => $manSet->id,
            'item_sku' => null,
            'note' => '-',
            'status' => 'removed',
            'is_additional' => false,
            'is_custom' => false,
            'rental_status' => 'rent',
            'default_item_type' => 'Celana',
            'price' => null,
            'discount' => null,
            'sort_order' => 1,
        ]);
    }
}

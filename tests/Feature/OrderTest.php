<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use App\Models\OrderSet;

test('can create order with all required fields', function () {
    $order = Order::create([
        'order_number' => 'ORD-TEST-' . uniqid(),
        'status' => 'draft',
        'customer_name' => 'Test Customer',
        'customer_address' => 'Test Address',
        'customer_phone_number' => '+62-812-1234-5678',
        'event_place' => 'Test Venue',
        'event_date' => '2025-12-31',
        'total_price' => 1000000,
        'total_discount' => 100000,
        'final_price' => 900000,
    ]);

    expect($order->order_number)->toContain('ORD-TEST-');
    expect($order->customer_name)->toBe('Test Customer');
    expect((float) $order->final_price)->toBe(900000.0);
});

test('can create order package with relationships', function () {
    $package = OrderPackage::create([
        'name' => 'Test Package',
        'price' => 500000,
        'discount' => 50000,
        'note' => 'Test note',
    ]);

    expect($package->name)->toBe('Test Package');
    expect((float) $package->price)->toBe(500000.0);
    expect((float) $package->discount)->toBe(50000.0);
});

test('can create order set with package relationship', function () {
    $package = OrderPackage::create([
        'name' => 'Test Package',
        'price' => 500000,
        'discount' => 50000,
    ]);

    $set = OrderSet::create([
        'order_package_id' => $package->id,
        'name' => 'Test Set',
    ]);

    expect($set->orderPackage->id)->toBe($package->id);
    expect($package->orderSets)->toHaveCount(1);
});

test('can create order item with all relationships', function () {
    $package = OrderPackage::create([
        'name' => 'Test Package',
        'price' => 500000,
        'discount' => 50000,
    ]);

    $set = OrderSet::create([
        'order_package_id' => $package->id,
        'name' => 'Test Set',
    ]);

    $item = OrderItem::create([
        'order_set_id' => $set->id,
        'item_sku' => 'KBY-RD-M',
        'note' => 'Test item',
        'status' => 'active',
        'is_additional' => false,
        'is_custom' => false,
        'rental_status' => 'rent',
        'default_item_type' => 'Kebaya',
        'price' => null,
        'discount' => null,
    ]);

    expect($item->orderSet->id)->toBe($set->id);
    expect($set->orderItems)->toHaveCount(1);
    expect($item->item_sku)->toBe('KBY-RD-M');
});

test('can create polymorphic order product relationship', function () {
    $order = Order::create([
        'order_number' => 'ORD-TEST-' . uniqid(),
        'status' => 'draft',
        'customer_name' => 'Test Customer',
        'customer_address' => 'Test Address',
        'customer_phone_number' => '+62-812-1234-5678',
        'event_place' => 'Test Venue',
        'event_date' => '2025-12-31',
        'total_price' => 1000000,
        'total_discount' => 100000,
        'final_price' => 900000,
    ]);

    $package = OrderPackage::create([
        'name' => 'Test Package',
        'price' => 500000,
        'discount' => 50000,
    ]);

    $orderProduct = OrderProduct::create([
        'order_id' => $order->id,
        'product_id' => $package->id,
        'product_type' => 'package',
        'sort_order' => 1,
    ]);

    expect($orderProduct->order->id)->toBe($order->id);
    expect($orderProduct->product->id)->toBe($package->id);
    expect($order->orderProducts)->toHaveCount(1);
});

test('can retrieve complete order structure with relationships', function () {
    $order = Order::with([
        'orderProducts.product',
        'packages.orderSets.orderItems.product'
    ])->first();
    
    expect($order)->not->toBeNull();
    expect($order->orderProducts)->not->toBeEmpty();
    
    foreach ($order->orderProducts as $orderProduct) {
        expect($orderProduct->product)->not->toBeNull();
        
        if ($orderProduct->product_type === 'Package') {
            $package = $orderProduct->product;
            expect($package->orderSets)->not->toBeEmpty();
            
            foreach ($package->orderSets as $set) {
                expect($set->orderItems)->not->toBeEmpty();
                
                foreach ($set->orderItems as $item) {
                    if ($item->item_sku) {
                        expect($item->product)->not->toBeNull();
                    }
                }
            }
        }
    }
});

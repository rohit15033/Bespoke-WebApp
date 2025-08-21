<?php

use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create and authenticate a user
    $user = User::factory()->create();
    Sanctum::actingAs($user);
});

test('can get orders list with pagination', function () {
    // Create some test orders
    Order::factory()->count(5)->create();

    $response = $this->getJson('/api/orders');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'current_page',
            'per_page',
            'total'
        ]);
});

test('can filter orders by status', function () {
    Order::factory()->create(['status' => 'draft']);
    Order::factory()->create(['status' => 'confirmed']);

    $response = $this->getJson('/api/orders?status=draft');

    $response->assertStatus(200);
    $this->assertEquals(1, count($response->json('data')));
});

test('can filter orders by customer name', function () {
    Order::factory()->create(['customer_name' => 'John Doe']);
    Order::factory()->create(['customer_name' => 'Jane Smith']);

    $response = $this->getJson('/api/orders?customer_name=John');

    $response->assertStatus(200);
    $this->assertEquals(1, count($response->json('data')));
});

test('can get order creation form data', function () {
    // Create some products for the form
    Item::factory()->count(3)->create();

    $response = $this->getJson('/api/orders/create');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'products',
            'statuses',
            'rental_statuses'
        ]);
});

test('can create a simple order', function () {
    $orderData = [
        'order_number' => 'ORD-TEST-001',
        'status' => 'draft',
        'customer_name' => 'Test Customer',
        'customer_address' => 'Test Address',
        'customer_phone_number' => '+62-812-1234-5678',
        'event_place' => 'Test Venue',
        'event_date' => '2025-12-31',
        'total_price' => 1000000,
        'total_discount' => 100000,
        'final_price' => 900000,
    ];

    $response = $this->postJson('/api/orders', $orderData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'order_number',
            'customer_name',
            'total_price',
            'final_price'
        ]);

    $this->assertDatabaseHas('orders', [
        'order_number' => 'ORD-TEST-001',
        'customer_name' => 'Test Customer'
    ]);
});

test('can create order with packages and items', function () {
    // Create a product for the test
    $item = Item::factory()->create();

    $orderData = [
        'order_number' => 'ORD-TEST-002',
        'status' => 'draft',
        'customer_name' => 'Test Customer',
        'customer_address' => 'Test Address',
        'customer_phone_number' => '+62-812-1234-5678',
        'event_place' => 'Test Venue',
        'event_date' => '2025-12-31',
        'total_price' => 2000000,
        'total_discount' => 200000,
        'final_price' => 1800000,
        'packages' => [
            [
                'name' => 'Test Package',
                'price' => 1500000,
                'discount' => 150000,
                'note' => 'Test package note',
                'sets' => [
                    [
                        'name' => 'Test Set',
                        'items' => [
                            [
                                'item_sku' => $item->sku,
                                'note' => 'Test item',
                                'status' => 'active',
                                'is_additional' => false,
                                'is_custom' => false,
                                'rental_status' => 'rent',
                                'default_item_type' => 'Kebaya',
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'standalone_items' => [
            [
                'item_sku' => $item->sku,
                'note' => 'Standalone item',
                'status' => 'active',
                'is_additional' => true,
                'is_custom' => false,
                'rental_status' => 'purchase',
                'default_item_type' => 'Accessory',
                'price' => 500000,
                'discount' => 50000,
            ]
        ]
    ];

    $response = $this->postJson('/api/orders', $orderData);

    $response->assertStatus(201);

    // Verify the order was created with relationships
    $order = Order::with(['packages.orderSets.orderItems', 'items'])->first();
    $this->assertEquals(1, $order->packages->count());
    $this->assertEquals(1, $order->packages->first()->orderSets->count());
    $this->assertEquals(1, $order->packages->first()->orderSets->first()->orderItems->count());
    $this->assertEquals(1, $order->items->count());
});

test('can get a specific order', function () {
    $order = Order::factory()->create();

    $response = $this->getJson("/api/orders/{$order->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $order->id,
            'order_number' => $order->order_number
        ]);
});

test('can get order edit form data', function () {
    $order = Order::factory()->create();
    Item::factory()->count(3)->create();

    $response = $this->getJson("/api/orders/{$order->id}/edit");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'order',
            'products',
            'statuses',
            'rental_statuses'
        ]);
});

test('can update an order', function () {
    $order = Order::factory()->create();

    $updateData = [
        'order_number' => $order->order_number,
        'status' => 'confirmed',
        'customer_name' => 'Updated Customer',
        'customer_address' => 'Updated Address',
        'customer_phone_number' => '+62-812-9876-5432',
        'event_place' => 'Updated Venue',
        'event_date' => '2025-11-15',
        'total_price' => 1500000,
        'total_discount' => 150000,
        'final_price' => 1350000,
    ];

    $response = $this->putJson("/api/orders/{$order->id}", $updateData);

    $response->assertStatus(200)
        ->assertJson([
            'customer_name' => 'Updated Customer',
            'status' => 'confirmed'
        ]);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'customer_name' => 'Updated Customer',
        'status' => 'confirmed'
    ]);
});

test('can delete an order', function () {
    $order = Order::factory()->create();

    $response = $this->deleteJson("/api/orders/{$order->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Order deleted successfully'
        ]);

    $this->assertDatabaseMissing('orders', [
        'id' => $order->id
    ]);
});

test('validates required fields when creating order', function () {
    $response = $this->postJson('/api/orders', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'order_number',
            'status',
            'customer_name',
            'customer_address',
            'customer_phone_number',
            'event_place',
            'event_date',
            'total_price',
            'total_discount',
            'final_price'
        ]);
});

test('validates unique order number', function () {
    $existingOrder = Order::factory()->create();

    $orderData = [
        'order_number' => $existingOrder->order_number,
        'status' => 'draft',
        'customer_name' => 'Test Customer',
        'customer_address' => 'Test Address',
        'customer_phone_number' => '+62-812-1234-5678',
        'event_place' => 'Test Venue',
        'event_date' => '2025-12-31',
        'total_price' => 1000000,
        'total_discount' => 100000,
        'final_price' => 900000,
    ];

    $response = $this->postJson('/api/orders', $orderData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['order_number']);
});

test('returns 404 for non-existent order', function () {
    $response = $this->getJson('/api/orders/99999');

    $response->assertStatus(404);
});

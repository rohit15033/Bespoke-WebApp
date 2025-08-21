<?php

use App\Models\ItemBlueprint;
use App\Models\PackageBlueprint;
use App\Models\Product;
use App\Models\SetBlueprint;

test('can create product with sku as primary key', function () {
    $product = Product::create([
        'sku' => 'TEST-SKU-' . uniqid(),
        'name' => 'Test Product',
        'type' => 'Kebaya',
        'color' => 'Red',
        'size' => 'M',
        'image_url' => 'https://example.com/test.jpg',
    ]);

    expect($product->sku)->toContain('TEST-SKU-');
    expect($product->name)->toBe('Test Product');
    expect($product->type)->toBe('Kebaya');
});

test('can create package blueprint with relationships', function () {
    $package = PackageBlueprint::create([
        'name' => 'Test Package',
        'default_price' => 1000000,
        'default_discount' => 5.00,
        'note' => 'Test note',
    ]);

    expect($package->name)->toBe('Test Package');
    expect((float) $package->default_price)->toBe(1000000.0);
    expect((float) $package->default_discount)->toBe(5.0);
});

test('can create set blueprint with package relationship', function () {
    $package = PackageBlueprint::create([
        'name' => 'Test Package',
        'default_price' => 1000000,
        'default_discount' => 5.00,
    ]);

    $set = SetBlueprint::create([
        'package_blueprint_id' => $package->id,
        'name' => 'Test Set',
        'sort_order' => 1,
    ]);

    expect($set->packageBlueprint->id)->toBe($package->id);
    expect($package->setBlueprints)->toHaveCount(1);
});

test('can create item blueprint with all relationships', function () {
    $product = Product::create([
        'sku' => 'TEST-SKU-' . uniqid(),
        'name' => 'Test Product',
        'type' => 'Kebaya',
        'color' => 'Blue',
        'size' => 'L',
    ]);

    $package = PackageBlueprint::create([
        'name' => 'Test Package',
        'default_price' => 1000000,
        'default_discount' => 5.00,
    ]);

    $set = SetBlueprint::create([
        'package_blueprint_id' => $package->id,
        'name' => 'Test Set',
        'sort_order' => 1,
    ]);

    $item = ItemBlueprint::create([
        'set_blueprint_id' => $set->id,
        'item_type' => 'Kebaya',
        'placeholder_sku' => $product->sku,
        'sort_order' => 1,
    ]);

    expect($item->setBlueprint->id)->toBe($set->id);
    expect($item->placeholderProduct->sku)->toBe($product->sku);
    expect($set->itemBlueprints)->toHaveCount(1);
    expect($product->itemBlueprints)->toHaveCount(1);
});

test('can retrieve complete package structure', function () {
    $package = PackageBlueprint::with(['setBlueprints.itemBlueprints.placeholderProduct'])->first();
    
    expect($package)->not->toBeNull();
    expect($package->setBlueprints)->not->toBeEmpty();
    
    foreach ($package->setBlueprints as $set) {
        expect($set->itemBlueprints)->not->toBeEmpty();
        
        foreach ($set->itemBlueprints as $item) {
            expect($item->placeholderProduct)->not->toBeNull();
        }
    }
});

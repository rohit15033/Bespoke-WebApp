<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemType;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use App\Models\OrderSet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::query()
            ->with(['salesperson1', 'salesperson2'])
            ->withSum('payments', 'amount');

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $status = $request->payment_status;
            if ($status === 'unpaid') {
                $query->doesntHave('payments');
            } elseif ($status === 'partial') {
                $query->whereHas('payments')
                      ->whereRaw('(select coalesce(sum(amount), 0) from payment_records where order_id = orders.id) < final_price');
            } elseif ($status === 'paid') {
                $query->whereRaw('(select coalesce(sum(amount), 0) from payment_records where order_id = orders.id) >= final_price');
            }
        }

        if ($request->has('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        if ($request->has('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        if ($request->has('event_date_from')) {
            $query->where('event_date', '>=', $request->event_date_from);
        }

        if ($request->has('event_date_to')) {
            $query->where('event_date', '<=', $request->event_date_to);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        // Handle sorting by computed column
        if ($sortBy === 'payment_status') {
             // Logic for sorting by payment status is complex, skipping for now or default to created_at
             // Alternatively could sort by ratio of paid/final
        } else {
             $sortOrder = $request->get('sort_order', 'desc');
             $query->orderBy($sortBy, $sortOrder);
        }


        // Pagination
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Validate order data
            $orderData = $request->validate([
                'order_number' => 'required|string|unique:orders,order_number',
                'status' => 'required|string|in:draft,confirmed,completed,cancelled',
                'customer_name' => 'required|string|max:255',
                'customer_address' => 'required|string',
                'customer_phone_number' => 'required|string|max:255',
                'event_place' => 'required|string|max:255',
                'event_date' => 'required|date',
                'total_price' => 'required|numeric|min:0',
                'total_discount' => 'required|numeric|min:0',
                'final_price' => 'required|numeric|min:0',
                'instagram_bride' => 'nullable|string|max:255',
                'instagram_groom' => 'nullable|string|max:255',
                'instagram_mua' => 'nullable|string|max:255',
                'instagram_hairdo' => 'nullable|string|max:255',
                'instagram_accessories' => 'nullable|string|max:255',
                'instagram_photography' => 'nullable|string|max:255',
                'instagram_wo' => 'nullable|string|max:255',
                'instagram_decor' => 'nullable|string|max:255',
                'salesperson1_id' => 'nullable|exists:users,id',
                'salesperson2_id' => 'nullable|exists:users,id',
            ]);

            // Create the order
            $order = Order::create($orderData);
            
            // Handle products (packages and standalone items)
            if ($request->has('products')) {
                $this->createOrderProducts($order, $request->products);
            }
            
            DB::commit();

            // Load relationships for response
            $order->load([
                'orderProducts',
                'packages.orderSets.orderItems.item',
                'items.item',
                'salesperson1',
                'salesperson2'
            ]);

            return response()->json($order, 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $order = Order::with([
            'orderProducts',
            'packages.orderSets.orderItems.item.subcolor',
            'packages.orderSets.orderItems.orderItemTypes',
            'items.item.subcolor',
            'items.orderItemTypes',
            'payments',
            'salesperson1',
            'salesperson2',
        ])->findOrFail($id);

        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            // Validate order data
            $orderData = $request->validate([
                'order_number' => 'required|string|unique:orders,order_number,' . $id,
                'status' => 'required|string|in:draft,confirmed,completed,cancelled',
                'customer_name' => 'required|string|max:255',
                'customer_address' => 'required|string',
                'customer_phone_number' => 'required|string|max:255',
                'event_place' => 'required|string|max:255',
                'event_date' => 'required|date',
                'total_price' => 'required|numeric|min:0',
                'total_discount' => 'required|numeric|min:0',
                'final_price' => 'required|numeric|min:0',
                'instagram_bride' => 'nullable|string|max:255',
                'instagram_groom' => 'nullable|string|max:255',
                'instagram_mua' => 'nullable|string|max:255',
                'instagram_hairdo' => 'nullable|string|max:255',
                'instagram_accessories' => 'nullable|string|max:255',
                'instagram_photography' => 'nullable|string|max:255',
                'instagram_wo' => 'nullable|string|max:255',
                'instagram_decor' => 'nullable|string|max:255',
                'salesperson1_id' => 'nullable|exists:users,id',
                'salesperson2_id' => 'nullable|exists:users,id',
            ]);

            // Update the order
            $order->update($orderData);

            // Delete existing packages
            $existingPackages = $order->packages;
            foreach ($existingPackages as $package) {
                // Delete order products for this package
                OrderProduct::where('order_id', $order->id)
                    ->where('product_id', $package->id)
                    ->where('product_type', 'package')
                    ->delete();
                
                $package->delete();
            }

            // Delete existing standalone items
            $existingItems = $order->items;
            foreach ($existingItems as $item) {
                // Delete order products for this item
                OrderProduct::where('order_id', $order->id)
                    ->where('product_id', $item->id)
                    ->where('product_type', 'item')
                    ->delete();
                
                $item->delete();
            }

            // Handle products (packages and standalone items)
            if ($request->has('products')) {
                $this->createOrderProducts($order, $request->products);
            }

            DB::commit();

            // Load relationships for response
            $order->load([
                'orderProducts',
                'packages.orderSets.orderItems.item.subcolor',
                'packages.orderSets.orderItems.orderItemTypes',
                'items.item.subcolor',
                'items.orderItemTypes',
                'salesperson1',
                'salesperson2',
            ]);

            return response()->json($order);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $order = Order::with([
                'orderProducts',
            ])->findOrFail($id);

            $this->authorize('delete', $order);

            foreach ($order->orderProducts as $productIndex => $productData) {
                $id = $productData->product_id;
                if ($productData->product_type == 'package') {
                    $package = OrderPackage::findOrFail($id);
                    $package->delete();
                } else if ($productData->product_type == 'item') {
                    $item = OrderItem::findOrFail($id);
                    $item->delete();
                }
            }

            $order->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create order products
     */
    private function createOrderProducts(Order $order, array $products)
    {
        foreach ($products as $productIndex => $productData) {
            // Validate product data
            $validatedProduct = Validator::make($productData, [
                'type' => 'required|string|in:package,item',
                'sort_order' => 'required|numeric|min:0',
                'item' => 'required_if:type,item|prohibited_unless:type,item',
                'package' => 'required_if:type,package|prohibited_unless:type,package',
            ])->validate();

            if ($validatedProduct['type'] == 'item') {
                $item = $this->createStandaloneItem(
                    $order, 
                    $validatedProduct['item']
                );

                // Link item to order
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product_type' => 'item',
                    'sort_order' => $validatedProduct['sort_order'],
                ]);
            } else if ($validatedProduct['type'] == 'package') {
                $package = $this->createOrderPackage(
                    $order,
                    $validatedProduct['package']
                );

                // Link package to order
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $package->id,
                    'product_type' => 'package',
                    'sort_order' => $validatedProduct['sort_order'],
                ]);
            }
        }
    }

    /**
     * Create an order package with their sets and items
     */
    private function createOrderPackage(Order $order, $packageData)
    {
        // Validate package data
        $validatedPackage = Validator::make($packageData, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'sets' => 'array',
        ])->validate();

        // Create package
        $package = OrderPackage::create([
            'name' => $validatedPackage['name'],
            'price' => $validatedPackage['price'],
            'discount' => $validatedPackage['discount'],
            'note' => $validatedPackage['note'] ?? null,
        ]);

        // Create sets and items if provided
        if (isset($validatedPackage['sets'])) {
            $this->createOrderSets($package, $validatedPackage['sets']);
        }

        return $package;
    }

    /**
     * Create order sets with their items
     */
    private function createOrderSets(OrderPackage $package, array $sets): void
    {
        foreach ($sets as $setIndex => $setData) {
            // Validate set data
            $validatedSet = Validator::make($setData, [
                'name' => 'required|string|max:255',
                'items' => 'array',
                'sort_order' => 'required|numeric|min:0',
            ])->validate();

            // Create set
            $set = OrderSet::create([
                'order_package_id' => $package->id,
                'name' => $validatedSet['name'],
                'sort_order' => $validatedSet['sort_order'],
            ]);

            // Create items if provided
            if (isset($validatedSet['items'])) {
                $this->createOrderItems($set, $validatedSet['items']);
            }
        }
    }

    /**
     * Create order items
     */
    private function createOrderItems(OrderSet $set, array $items): void
    {
        foreach ($items as $itemIndex => $itemData) {
            // Validate item data
            $validatedItem = Validator::make($itemData, [
                'item_id' => 'nullable|numeric|min:0',
                'note' => 'nullable|string',
                'status' => 'required|string|in:active,removed',
                'is_additional' => 'boolean',
                'is_custom' => 'boolean',
                'is_tentative' => 'boolean',
                'rental_status' => 'required|string|in:rent,purchase',
                'description' => 'nullable|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'custom_name' => 'nullable|string|max:255',
                'custom_type' => 'nullable|string|max:255',
                'custom_details' => 'nullable|string',
                'sort_order' => 'required|numeric|min:0',
                'order_item_types' => 'array',
            ])->validate();

            $needSku = $validatedItem['status'] == 'active' && !$validatedItem['is_tentative'] && !$validatedItem['is_custom'];

            // Create item
            $orderItem = OrderItem::create([
                'order_set_id' => $set->id,
                'item_id' => $needSku ? $validatedItem['item_id'] : null,
                'note' => $validatedItem['note'] ?? null,
                'status' => $validatedItem['status'],
                'is_additional' => $validatedItem['is_additional'] ?? false,
                'is_custom' => $validatedItem['is_custom'] ?? false,
                'is_tentative' => $validatedItem['is_tentative'] ?? false,
                'rental_status' => $validatedItem['rental_status'],
                'description' => $validatedItem['description'] ?? null,
                'price' => $validatedItem['price'] ?? null,
                'discount' => $validatedItem['discount'] ?? null,
                'custom_name' => $validatedItem['custom_name'] ?? null,
                'custom_type' => $validatedItem['custom_type'] ?? null,
                'custom_details' => $validatedItem['custom_details'] ?? null,
                'sort_order' => $validatedItem['sort_order'],
            ]);

            // Create item types if provided
            if (isset($validatedItem['order_item_types'])) {
                $this->createOrderItemTypes($orderItem, $validatedItem['order_item_types']);
            }
        }
    }

    /**
     * Create a standalone item (not part of any package)
     */
    private function createStandaloneItem(Order $order, $itemData)
    {
        // Validate item data
        $validatedItem = Validator::make($itemData, [
            'item_id' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'is_additional' => 'boolean',
            'is_custom' => 'boolean',
            'rental_status' => 'required|string|in:rent,purchase',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'custom_name' => 'nullable|string|max:255',
            'custom_type' => 'nullable|string|max:255',
            'custom_details' => 'nullable|string',
            'order_item_types' => 'array',
        ])->validate();

        $needSku = !$validatedItem['is_custom'];

        // Create standalone item
        $orderItem = OrderItem::create([
            'order_set_id' => null,
            'item_id' => $needSku ? $validatedItem['item_id'] : null,
            'note' => $validatedItem['note'] ?? null,
            'status' => null,
            'is_additional' => $validatedItem['is_additional'] ?? false,
            'is_custom' => $validatedItem['is_custom'] ?? false,
            'rental_status' => $validatedItem['rental_status'],
            'description' => null,
            'price' => $validatedItem['price'],
            'discount' => $validatedItem['discount'],
            'custom_name' => $validatedItem['custom_name'] ?? null,
            'custom_type' => $validatedItem['custom_type'] ?? null,
            'custom_details' => $validatedItem['custom_details'] ?? null,
        ]);

        // Create item types if provided
        if (isset($validatedItem['order_item_types'])) {
            $this->createOrderItemTypes($orderItem, $validatedItem['order_item_types']);
        }

        return $orderItem;
    }

    private function createOrderItemTypes(OrderItem $orderItem, array $orderItemTypeList)
    {
        foreach ($orderItemTypeList as $itemType) {
            $validatedItemType = Validator::make($itemType, [
                "name" => "required|string|max:255",
                'sort_order' => 'required|numeric|min:0',
            ])->validate();

            OrderItemType::create([
                'order_item_id' => $orderItem->id,
                'name' => $validatedItemType['name'],
                'sort_order' => $validatedItemType['sort_order'],
            ]);
        }
    }

    /**
     * Display listing of events (orders with payments).
     */
    public function events(Request $request): JsonResponse
    {
        $query = Order::whereHas('payments')
            ->orderBy('event_date', 'asc');

        if ($request->has('from')) {
            $query->where('event_date', '>=', $request->from);
        }
        if ($request->has('to')) {
            $query->where('event_date', '<=', $request->to);
        }

        $events = $query->get([
            'id', 
            'customer_name', 
            'event_date', 
            'event_place', 
            'order_number',
            'instagram_bride',
            'instagram_groom',
            'instagram_mua',
            'instagram_hairdo',
            'instagram_accessories',
            'instagram_photography',
            'instagram_wo',
            'instagram_decor'
        ]);

        return response()->json($events);
    }
}

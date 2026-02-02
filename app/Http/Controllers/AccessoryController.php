<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Items;
use App\Http\Requests\Inventory\StoreAccessoryRequest;
use App\Http\Requests\Inventory\UpdateAccessoryRequest;
use App\Traits\HandlesInventoryImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessoryController extends Controller
{
    use HandlesInventoryImages;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payload = [
            'search_filter' => $request->input('search_filter', null),
            'color' => $request->input('color', null),
            'subcolor' => $request->input('subcolor', null),
            'fromMonth' => $request->input('from_month', null),
            'toMonth' => $request->input('to_month', null),
            'fromYear' => $request->input('from_year', null),
            'toYear' => $request->input('to_year', null),
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'sort' => $request->input('sort', 'created_at'),
            'accessories_type' => $request->input('accessories_type', null),
            'parent_type' => $request->input('parent_type', null)
        ];

        $accessoriesList = Accessory::getAccessoriesList($payload);
        
        return response()->json([
            'message' => "Accessories List has been retrieved",
            'accessories' => $accessoriesList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccessoryRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $validated = $request->validated();

                $item = Items::create([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'type' => 'accessories',
                    'production_month' => $validated['production_month'],
                    'production_year' => $validated['production_year'],
                    'subcolor_id' => $validated['subcolor_id'],
                ]);

                $accessory = Accessory::create([
                    'item_id' => $item->id,
                    'accessories_type' => $validated['accessories_type'],
                    'parent_type' => $validated['parent_type'] ?? null,
                ]);

                $this->uploadImages($item, $request->file('images'));

                return response()->json([
                    'message' => "Accessory created successfully",
                    'data' => $item
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating Accessory: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $accessory = Accessory::getAccessoryById($id);
        
        if (!$accessory) {
            return response()->json(['message' => 'Accessory not found'], 404);
        }

        return response()->json($accessory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccessoryRequest $request, $id)
    {
        try {
            $accessory = Accessory::findOrFail($id);

            return DB::transaction(function () use ($request, $accessory) {
                $validated = $request->validated();

                $item = Items::findOrFail($accessory->item_id);
                $item->update([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'production_month' => $validated['production_month'],
                    'production_year' => $validated['production_year'],
                    'subcolor_id' => $validated['subcolor_id'],
                ]);

                if ($accessory->accessories_type === 'Bros' && isset($validated['parent_type'])) {
                    $accessory->update(['parent_type' => $validated['parent_type']]);
                } else {
                    $accessory->update([
                        'accessories_type' => $validated['accessories_type'],
                    ]);
                }

                $this->updateImages(
                    $item, 
                    $request->input('existing_images', []), 
                    $request->file('new_images', [])
                );

                return response()->json([
                    'message' => "Accessory updated successfully",
                    'data' => $accessory
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating Accessory: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $accessory = Accessory::findOrFail($id);

            return DB::transaction(function () use ($accessory) {
                if ($accessory->item_id) {
                    $item = Items::find($accessory->item_id);
                    if ($item) {
                        $this->deleteImages($item);
                        $item->delete();
                    }
                }
                
                $accessory->delete();

                return response()->json([
                    'message' => 'Accessory deleted successfully',
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting accessory: ' . $e->getMessage(),
            ], 404);
        }
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Kebaya;
use App\Models\Items;
use App\Http\Requests\Inventory\StoreKebayaRequest;
use App\Http\Requests\Inventory\UpdateKebayaRequest;
use App\Traits\HandlesInventoryImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KebayaController extends Controller
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
            'occasion' => $request->input('occasion', null),
            'fromMonth' => $request->input('from_month', null),
            'toMonth' => $request->input('to_month', null),
            'fromYear' => $request->input('from_year', null),
            'toYear' => $request->input('to_year', null),
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'sort' => $request->input('sort', 'created_at'),
        ];
        
        $kebayaList = Kebaya::getKebayaList($payload);

        return response()->json([
            'message' => "Kebaya List has been retrieved",
            'kebayas' => $kebayaList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKebayaRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $validated = $request->validated();

                $item = Items::create([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'type' => 'kebaya',
                    'production_month' => $validated['production_month'],
                    'production_year'  => $validated['production_year'],
                    'subcolor_id' => $validated['subcolor_id'],
                ]);

                $kebaya = Kebaya::create([
                    'item_id' => $item->id,
                    'length' => $validated['length']
                ]);

                if (!empty($validated['occasion_ids'])) {
                    $kebaya->occasions()->sync($validated['occasion_ids']);
                }

                $this->uploadImages($item, $request->file('images'));

                return response()->json([
                    'message' => "Kebaya created successfully",
                    'data' => $item
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating kebaya: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kebaya = Kebaya::getKebayaById($id);
        
        if (!$kebaya) {
            return response()->json(['message' => 'Kebaya not found'], 404);
        }

        return response()->json($kebaya);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKebayaRequest $request, $id)
    {
        try {
            $kebaya = Kebaya::findOrFail($id);

            return DB::transaction(function () use ($request, $kebaya) {
                $validated = $request->validated();

                $item = Items::findOrFail($kebaya->item_id);
                $item->update([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'production_month' => $validated['production_month'],
                    'production_year'  => $validated['production_year'],
                    'subcolor_id' => $validated['subcolor_id'],
                ]);

                $kebaya->update([
                    'length' => $validated['length']
                ]);

                if ($request->has('occasion_ids')) {
                    $kebaya->occasions()->sync($validated['occasion_ids']);
                }

                $this->updateImages(
                    $item, 
                    $request->input('existing_images', []), 
                    $request->file('new_images', [])
                );

                return response()->json([
                    'message' => "Kebaya updated successfully",
                    'data' => $kebaya
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating kebaya: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kebaya = Kebaya::findOrFail($id);

            return DB::transaction(function () use ($kebaya) {
                if ($kebaya->item_id) {
                    $item = Items::find($kebaya->item_id);
                    if ($item) {
                        $this->deleteImages($item);
                        $item->delete();
                    }
                }
                
                $kebaya->delete();

                return response()->json([
                    'message' => 'Kebaya deleted successfully',
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting kebaya: ' . $e->getMessage(),
            ], 404);
        }
    }
}

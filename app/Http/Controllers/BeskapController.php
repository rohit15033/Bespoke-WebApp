<?php

namespace App\Http\Controllers;

use App\Models\Beskap;
use App\Models\Items;
use App\Http\Requests\Inventory\StoreBeskapRequest;
use App\Http\Requests\Inventory\UpdateBeskapRequest;
use App\Traits\HandlesInventoryImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeskapController extends Controller
{
    use HandlesInventoryImages;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payload = [
            'search_filter' => $request->input('search_filter', null),
            'type' => $request->input('type', null),
            'color' => $request->input('color', null),
            'subcolor' => $request->input('subcolor', null),
            'fromMonth' => $request->input('from_month', null),
            'toMonth' => $request->input('to_month', null),
            'fromYear' => $request->input('from_year', null),
            'toYear' => $request->input('to_year', null),
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'sort' => $request->input('sort', 'created_at'),
        ];
        
        $beskapList = Beskap::getBeskapList($payload);

        return response()->json([
            'message' => "Beskap List has been retrieved",
            'beskaps' => $beskapList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBeskapRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $validated = $request->validated();

                $item = Items::create([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'type' => 'beskap',
                    'production_month' => $validated['production_month'],
                    'production_year' => $validated['production_year'],
                    'subcolor_id' => $validated['subcolor_id'],
                ]);

                $beskap = Beskap::create([
                    'item_id' => $item->id,
                    'type' => $validated['type'],
                ]);

                $this->uploadImages($item, $request->file('images'));

                return response()->json([
                    'message' => "Beskap created successfully",
                    'data' => $item
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating Beskap: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $beskap = Beskap::getBeskapById($id);
        
        if (!$beskap) {
            return response()->json(['message' => 'Beskap not found'], 404);
        }

        return response()->json($beskap);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBeskapRequest $request, $id)
    {
        try {
            $beskap = Beskap::findOrFail($id);

            return DB::transaction(function () use ($request, $beskap) {
                $validated = $request->validated();

                $item = Items::findOrFail($beskap->item_id);
                $item->update([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'production_month' => $validated['production_month'],
                    'production_year' => $validated['production_year'],
                    'subcolor_id' => $validated['subcolor_id'],
                ]);

                $beskap->update([
                    'type' => $validated['type'],
                ]);

                $this->updateImages(
                    $item, 
                    $request->input('existing_images', []), 
                    $request->file('new_images', [])
                );

                return response()->json([
                    'message' => "Beskap updated successfully",
                    'data' => $beskap
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating Beskap: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $beskap = Beskap::findOrFail($id);

            return DB::transaction(function () use ($beskap) {
                if ($beskap->item_id) {
                    $item = Items::find($beskap->item_id);
                    if ($item) {
                        $this->deleteImages($item);
                        $item->delete();
                    }
                }
                
                $beskap->delete();

                return response()->json([
                    'message' => 'Beskap deleted successfully',
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting beskap: ' . $e->getMessage(),
            ], 404);
        }
    }
}

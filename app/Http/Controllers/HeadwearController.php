<?php

namespace App\Http\Controllers;

use App\Models\Headwear;
use App\Models\Items;
use App\Models\ItemsImagesUrls;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HeadwearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $payload = [
            'search_filter' => $request->input('search_filter', null), // Optional filter parameter 
            'color' => $request->input('color', null), // Optional filter parameter  
            'subcolor' => $request->input('subcolor', null), // Optional filter parameter
            'fromMonth' => $request->input('from_month', null),
            'toMonth' => $request->input('to_month', null),
            'fromYear' => $request->input('from_year', null),
            'toYear' => $request->input('to_year', null),
            'page' => $request->input('page', 1), // Default to page 1 if not provided
            'limit' => $request->input('limit', 10), // Default to 5 items per page if not provided
            'sort' => $request->input('sort', 'created_at'), // 
            'headwear_type' => $request->input('headwear_type', null), // Optional filter parameter 
            'adat' => $request->input('adat', null)
        ];

        $headwearList = Headwear::getHeadwearList($payload);
        return response()->json([
            'message' => "Headwear List has been retrieved",
            'headwears' => $headwearList
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'production_month' => in_array($request->production_month, [null, '', 'null', 'Choose Month'])
                ? null
                : $request->production_month,
            'production_year' => in_array($request->production_year, [null, '', 'null', 'Choose Year'])
                ? null
                : $request->production_year,
        ]);

        //
        try {
            //code...
            $validated = $request->validate([
                'code' => 'required|string|max:255|unique:items,code',
                'name' => 'required|string|max:255',
                'production_month' => 'nullable|integer',
                'production_year' => 'nullable|integer',
                'subcolor_id' => 'required|exists:subcolors,id',
                'images' => 'required',
                'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:2048',
                'headwear_type' => ['required', Rule::in(['Blangkon', 'Peci', 'Tanjak'])],
                'adat' => 'nullable|string|max:255',
            ]);
            $item = Items::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'type' => 'headwear', // identify it's headwear
                'production_month' => isset($validated['production_month']) ? (int) $validated['production_month'] : null,
                'production_year' => isset($validated['production_year']) ? (int) $validated['production_year'] : null,
                'subcolor_id' => $validated['subcolor_id'],
            ]);
            Headwear::create([
                'item_id' => $item->id,
                'headwear_type' => $validated['headwear_type'],
                'adat' => $validated['adat'] ?? null,
            ]);
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('items_images', 'public');
                    ItemsImagesUrls::create([
                        'item_id' => $item->id,
                        'image_url' => $path,
                    ]);
                }
            }
            return response()->json([
                'message' => "Headwear created successfully",
                'data' => $item
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating Headwear: ' . $e->getMessage(),
            ], 500); // 500 = Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $headwear = Headwear::getHeadwearById($id);
        return response()->json($headwear);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Headwear $headwear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->merge([
            'production_month' => in_array($request->production_month, [null, '', 'null', 'Choose Month'])
                ? null
                : $request->production_month,
            'production_year' => in_array($request->production_year, [null, '', 'null', 'Choose Year'])
                ? null
                : $request->production_year,
        ]);

        //
        $headwear = Headwear::findOrFail($id);
        if (!$headwear) {
            return response()->json([
                'message' => 'Headwear not found',
            ], 404);
        }
        try {
            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('items', 'code')->ignore($headwear->item_id),
                ],
                'name' => 'required|string|max:255',
                'production_month' => 'nullable|integer',
                'production_year' => 'nullable|integer',
                'subcolor_id' => 'required|exists:subcolors,id',
                'images' => 'nullable',
                'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:2048',
                'adat' => 'nullable|string|max:255',
                'headwear_type' => ['required', Rule::in(['Blangkon', 'Peci', 'Tanjak'])],
            ]);
            $item = Items::findOrFail($headwear->item_id);
            $item->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'type' => 'headwear', // identify it's headwear
                'production_month' => isset($validated['production_month']) ? (int) $validated['production_month'] : null,
                'production_year' => isset($validated['production_year']) ? (int) $validated['production_year'] : null,
                'subcolor_id' => $validated['subcolor_id'],
            ]);
            $headwear = Headwear::where('item_id', $headwear->item_id)->first();
            if ($headwear->headwear_type === 'blangkon' && isset($validated['adat'])) {
                $headwear->update(['adat' => $validated['adat']]);
            } else if ($headwear) {
                $headwear->update([
                    'headwear_type' => $validated['headwear_type'],
                ]);
            }

            $existingImageIds = $request->input('existing_images', []);
            $newImages = $request->file('new_images', []);
            $item->images()->whereNotIn('id', $existingImageIds)->get()->each(function ($img) {
                Storage::disk('public')->delete($img->image_url);
                $img->delete();
            });
            foreach ($newImages as $file) {
                $path = $file->store('items_images', 'public'); // store in storage/app/public/kebaya_images
                $item->images()->create([
                    'image_url' => $path,
                ]);
            }

            return response()->json([
                'message' => "Headwear updated successfully",
                'data' => $headwear
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating Headwear: ' . $e->getMessage(),
            ], 500); // 500 = Internal Server Error

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $deletedCount = Headwear::destroy($id); // Returns 1 if deleted, 0 if not found

        if ($deletedCount === 0) {
            return response()->json([
                'message' => 'Headwear not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Headwear deleted successfully',
        ], 200);
    }
}

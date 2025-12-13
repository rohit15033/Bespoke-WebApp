<?php

namespace App\Http\Controllers;

use App\Models\Bustier;
use App\Models\Items;
use Illuminate\Http\Request;
use App\Models\ItemsImagesUrls;
use Illuminate\Validation\Rule;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class BustierController extends Controller
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
        ];
        $bustierList = Bustier::getBustierList($payload);

        return response()->json([
            'message' => "Bustier List has been retrieved",
            'bustier' => $bustierList
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
        {
            try {
                $validated = $request->validate([
                    'code' => 'required|string|max:255|unique:items,code',
                    'name' => 'required|string|max:255',
                    'qty' => 'required|integer',
                    'production_month' => 'nullable|integer',
                    'production_year' => 'nullable|integer',
                    'subcolor_id' => 'required|exists:subcolors,id',
                    'images' => 'required',
                    'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
                ]);
                $item = Items::create([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'type' => 'bustier', // identify it's bustier
                    'production_month' => isset($validated['production_month']) ? (int) $validated['production_month'] : null,
                    'production_year' => isset($validated['production_year']) ? (int) $validated['production_year'] : null,
                    'subcolor_id' => $validated['subcolor_id'],
                ]);
                Bustier::create([
                    'item_id' => $item->id,
                    'qty' => $validated['qty'],
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
                    'message' => "Bustier created successfully",
                    'data' => $item
                ], 201);
            } catch (ValidationException $e) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error creating Bustier: ' . $e->getMessage(),
                ], 500); // 500 = Internal Server Error
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $bustier = Bustier::getBustierById($id);
        return response()->json($bustier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(bustier $bustier)
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
        $bustier = Bustier::findOrFail($id);
        if (!$bustier) {
            return response()->json([
                'message' => 'Bustier not found',
            ], 404);
        }
        try {
            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('items', 'code')->ignore($bustier->item_id),
                ],
                'name' => 'required|string|max:255',
                'qty' => 'required|integer',
                'production_month' => 'nullable|integer',
                'production_year' => 'nullable|integer',
                'subcolor_id' => 'required|exists:subcolors,id',
                'images' => 'nullable',
                'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
            ]);
            $item = Items::findOrFail($bustier->item_id);
            $item->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'type' => 'bustier', // identify it's bustier
                'production_month' => isset($validated['production_month']) ? (int) $validated['production_month'] : null,
                'production_year' => isset($validated['production_year']) ? (int) $validated['production_year'] : null,
                'subcolor_id' => $validated['subcolor_id'],
            ]);
            if (isset($validated['qty'])) {
                $bustier = Bustier::where('item_id', $bustier->item_id)->first();
                if ($bustier) {
                    $bustier->update([
                        'qty' => $validated['qty'],
                    ]);
                }
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
                'message' => "Bustier updated successfully",
                'data' => $bustier
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating Bustier: ' . $e->getMessage(),
            ], 500); // 500 = Internal Server Error

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(bustier $bustier)
    {
        //
        $deletedCount = Bustier::destroy($bustier->id); // Returns 1 if deleted, 0 if not found

        if ($deletedCount === 0) {
            return response()->json([
                'message' => 'Bustier not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Bustier deleted successfully',
        ], 200);
    }
}

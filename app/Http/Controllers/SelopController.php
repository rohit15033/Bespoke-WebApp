<?php

namespace App\Http\Controllers;

use App\Models\Selop;
use App\Models\Items;
use App\Models\ItemsImagesUrls;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class SelopController extends Controller
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
        $selopList = Selop::getSelopList($payload);

        return response()->json([
            'message' => "Selop List has been retrieved",
            'selop' => $selopList
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
                    'size' => 'required|string|max:50',
                    'production_month' => 'nullable|integer',
                    'production_year' => 'nullable|integer',
                    'subcolor_id' => 'required|exists:subcolors,id',
                    'images' => 'required',
                    'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
                ]);
                $item = Items::create([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'type' => 'selop', // identify it's selop
                    'production_month' => isset($validated['production_month']) ? (int) $validated['production_month'] : null,
                    'production_year' => isset($validated['production_year']) ? (int) $validated['production_year'] : null,
                    'subcolor_id' => $validated['subcolor_id'],
                ]);
                Selop::create([
                    'item_id' => $item->id,
                    'size' => $validated['size'],
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
                    'message' => "Selop created successfully",
                    'data' => $item
                ], 201);
            } catch (ValidationException $e) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error creating Selop: ' . $e->getMessage(),
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
        $selop = Selop::getSelopById($id);
        return response()->json($selop);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Selop $selop)
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
        $selop = Selop::findOrFail($id);
        if (!$selop) {
            return response()->json([
                'message' => 'Selop not found',
            ], 404);
        }
        try {
            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('items', 'code')->ignore($selop->item_id),
                ],
                'name' => 'required|string|max:255',
                'size' => 'required|string|max:50',
                'production_month' => 'nullable|integer',
                'production_year' => 'nullable|integer',
                'subcolor_id' => 'required|exists:subcolors,id',
                'images' => 'nullable',
                'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
            ]);
            $item = Items::findOrFail($selop->item_id);
            $item->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'type' => 'selop', // identify it's selop
                'production_month' => isset($validated['production_month']) ? (int) $validated['production_month'] : null,
                'production_year' => isset($validated['production_year']) ? (int) $validated['production_year'] : null,
                'subcolor_id' => $validated['subcolor_id'],
            ]);
            if (isset($validated['size'])) {
                $selop = Selop::where('item_id', $selop->item_id)->first();
                if ($selop) {
                    $selop->update([
                        'size' => $validated['size'],
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
                'message' => "Selop updated successfully",
                'data' => $selop
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating Selop: ' . $e->getMessage(),
            ], 500); // 500 = Internal Server Error

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Selop $selop)
    {
        //
        $deletedCount = Selop::destroy($selop->id); // Returns 1 if deleted, 0 if not found

        if ($deletedCount === 0) {
            return response()->json([
                'message' => 'Selop not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Selop deleted successfully',
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kebaya;
use App\Models\Items;
use App\Models\ItemsImagesUrls;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KebayaController extends Controller
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
            'occasion' => $request->input('occasion', null),
            'fromMonth' => $request->input('from_month', null),
            'toMonth' => $request->input('to_month', null),
            'fromYear' => $request->input('from_year', null),
            'toYear' => $request->input('to_year', null),
            'page' => $request->input('page', 1), // Default to page 1 if not provided
            'limit' => $request->input('limit', 10), // Default to 5 items per page if not provided
            'sort' => $request->input('sort', 'created_at'), // 
        ];
        $kebayaList = Kebaya::getKebayaList($payload);

        return response()->json([
            'message' => "Kebaya List has been retrieved",
            'kebayas' => $kebayaList
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

        try {
            $validated = $request->validate([
                'code' => 'required|string|max:255|unique:items,code',
                'name' => 'required|string|max:255',
                'production_month' => 'nullable|integer',
                'production_year' => 'nullable|integer',
                'subcolor_id' => 'required|exists:subcolors,id',
                'images' => 'required',
                'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
                'length' => 'required|string|max:255',
                'occasion_ids' => 'nullable|array',
                'occasion_ids.*' => 'exists:occasions,id',
            ]);
            $item = Items::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'type' => 'kebaya', // identify it's kebaya
                'production_month' => isset($validated['production_month']) ? (int) $validated['production_month'] : null,
                'production_year'  => isset($validated['production_year']) ? (int) $validated['production_year'] : null,

                'subcolor_id' => $validated['subcolor_id'],
            ]);
            $kebayaData = Kebaya::create([
                'item_id' => $item->id,
                'length' => $validated['length']
            ]);
            if (!empty($validated['occasion_ids'])) {
                $kebayaData->occasions()->sync($validated['occasion_ids']);
            }
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
                'message' => "Kebaya created successfully",
                'data' => $item
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating kebaya: ' . $e->getMessage(),
            ], 500); // 500 = Internal Server Error
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $kebaya = Kebaya::getKebayaById($id);
        return response()->json($kebaya);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kebaya $kebaya)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->merge([
            'production_month' => in_array($request->production_month, [null, '', 'null', 'Choose Month'])
                ? null
                : $request->production_month,
            'production_year' => in_array($request->production_year, [null, '', 'null', 'Choose Year'])
                ? null
                : $request->production_year,
        ]);
        $kebaya = Kebaya::findOrFail($id);
        if (!$kebaya) {
            return response()->json([
                'message' => 'Kebaya not found',
            ], 404);
        }
        try {

            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('items')->ignore($id),
                ],
                'name' => 'required|string|max:255',
                'production_month' => 'nullable|integer',
                'production_year' => 'nullable|integer',
                'subcolor_id' => 'required|exists:subcolors,id',
                'images' => 'nullable',
                'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
                'length' => 'required|string|max:255',
                'occasion_ids' => 'nullable|array',
                'occasion_ids.*' => 'exists:occasions,id',
            ]);
            $item = Items::findOrFail($kebaya->item_id);
            $item->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'type' => 'kebaya', // identify it's kebaya
                'production_month' => isset($validated['production_month']) ? (int) $validated['production_month'] : null,
                'production_year'  => isset($validated['production_year']) ? (int) $validated['production_year'] : null,
                'subcolor_id' => $validated['subcolor_id'],
            ]);
            $kebaya->update([
                'length' => $validated['length']
            ]);
            if ($request->has('occasion_ids')) {
                $kebaya->occasions()->sync($validated['occasion_ids']); // sync handles add/remove
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
                'message' => "Kebaya updated successfully",
                'data' => $kebaya
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating kebaya: ' . $e->getMessage(),
            ], 500); // 500 = Internal Server Error

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete-inventory');
        $kebaya = Kebaya::findOrFail($id);

        if ($kebaya->item_id) {
             $item = Items::find($kebaya->item_id);
             if ($item) {
                 // Delete images
                 foreach ($item->images as $img) {
                     Storage::disk('public')->delete($img->image_url);
                     $img->delete();
                 }
                 $item->delete();
             }
        }
        $kebaya->delete();

        return response()->json([
            'message' => 'Kebaya deleted successfully',
        ], 200);
    }
}

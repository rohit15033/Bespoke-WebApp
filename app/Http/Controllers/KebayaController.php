<?php

namespace App\Http\Controllers;
use App\Models\Kebaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KebayaController extends Controller
{
    //

    public function index(Request $request){
        //
        $payload = [
            'kebaya_filter' => $request->input('kebaya_filter', null), // Optional filter parameter 
            'color' => $request->input('color', null), // Optional filter parameter  
            'subcolor' => $request->input('subcolor', null), // Optional filter parameter
            'occasion' => $request->input('occasion', null), // Optional filter parameter
            'fromAt' => $request->input('fromAt', null),
            'toAt' => $request->input('toAt', null),
            'page' => $request->input('page', 1), // Default to page 1 if not provided
            'limit' => $request->input('limit', 10), // Default to 5 items per page if not provided
            'sort' => $request->input('sort', 'production_date'), // Default sort by 'production_date' field    
        ];

        $kebayaList = Kebaya::getKebayaList($payload);
        return response()->json([
            'message' => 'Kebaya list retrieved successfully',
            'data' => $kebayaList
        ], 200); // 200 = OK
    }

    public function store(Request $request){
        
        try {
            $validated = $request->validate([
                'kebayaCode' => 'required|string|max:255|',
                'kebayaName' => 'required|string|max:255',
                'length' => 'required|string|max:50',
                'production_date' => 'required|date',
                'subcolor_id' => 'required|exists:subcolors,id',
                'occasion_ids' => 'nullable|array',
                'occasion_ids.*' => 'exists:occasions,id',
                'images' => 'nullable',
                'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:2048',
              
            ]);

            $kebayaData = Kebaya::create([
                'code' => $validated['kebayaCode'],
                'name' => $validated['kebayaName'],
                'length' => $validated['length'],
                'production_date' => $validated['production_date'],
                'subcolor_id' => $validated['subcolor_id'],
            ]);

            if (!empty($validated['occasion_ids'])) {
                $kebayaData->occasions()->sync($validated['occasion_ids']);
            }

            if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        // Store file in /storage/app/public/kebaya
                        $path = $file->store('kebaya', 'public');

                        // Save file path in kebaya_images table (assuming you have relation)
                        $kebayaData->images()->create([
                            'image_url' => $path,
                        ]);
                    }
            }


            return response()->json([
                'message' => 'Kebaya created successfully',
                'data' => $kebayaData
            ], 201); // 201 = Created

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating kebaya: ' . $e->getMessage(),
            ], 500); // 500 = Internal Server Error
        }
       
    }

    public function show($id){
        //
        $kebayaData = Kebaya::getKebayaById($id);
        return response()->json($kebayaData);
    }

    public function update(Request $request, $id){
        //
       
        $kebaya = Kebaya::findOrFail($id);

        $kebaya->code = $request->input('kebayaCode');
        $kebaya->name = $request->input('kebayaName');
        $kebaya->subcolor_id = $request->input('subcolor_id');
        $kebaya->length = $request->input('length');
        $kebaya->production_date = $request->input('production_date');
        $kebaya->save();

         //  Handle occasions
        if ($request->has('occasion_ids')) {
            $kebaya->occasions()->sync($request->occasion_ids); // sync handles add/remove
        }

        $existingImageIds = $request->input('existing_images', []);
        $newImages = $request->file('new_images', []);

        $kebaya->images()->whereNotIn('id', $existingImageIds)->each(function($img){
            Storage::disk('public')->delete($img->image_url);
            $img->delete();
        });

        foreach ($newImages as $file) {
            $path = $file->store('kebaya_images', 'public'); // store in storage/app/public/kebaya_images
            $kebaya->images()->create([
                'image_url' => $path,
            ]);
        }

        return response()->json([
            'message' => 'Kebaya updated successfully',
            'data' => $kebaya->load('images', 'occasions'),
        ]);
    
    }

    public function destroy($id){
        //
        $deletedCount = Kebaya::destroy($id); // Returns 1 if deleted, 0 if not found

        if ($deletedCount === 0) {
            return response()->json([
                'message' => 'Kebaya not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Kebaya deleted successfully',
        ], 200);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Items::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        $query->with('subcolor');

        $items = $query->paginate($request->get('per_page', 15));
        return response()->json($items);
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:items,code',
            'type' => 'required|string|max:255',
            'production_month' => 'nullable|integer|min:1|max:12',
            'production_year' => 'nullable|integer|min:1900|max:2100',
            'subcolor_id' => 'nullable|exists:subcolors,id',
        ]);

        $item = Items::create($validated);
        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Items $items)
    {
        $items->load(['subcolor', 'images', 'firstImage']);
        return response()->json($items);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Items $items)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Items $items)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|unique:items,code,' . $items->id,
            'type' => 'sometimes|string|max:255',
            'production_month' => 'nullable|integer|min:1|max:12',
            'production_year' => 'nullable|integer|min:1900|max:2100',
            'subcolor_id' => 'nullable|exists:subcolors,id',
        ]);

        $items->update($validated);
        return response()->json($items);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Items $items)
    {
        $this->authorize('delete-inventory');
        $items->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }

    public function getItemCode(Request $request)
    {
        $code = $request->input('code');
        $id = $request->input('id');

        // 🧠 If updating and code is the same, just return it
        if ($id) {
            $item = Items::find($id);
            if ($item && str_starts_with($item->code, $code)) {
                return response()->json(['data' => $item->code]);
            }
        }

        // Generate next available code
        $latest = Items::where('code', 'like', "$code%")->orderBy('code', 'desc')->first();
        $nextNumber = $latest ? str_pad((int) substr($latest->code, strrpos($latest->code, '-') + 1) + 1, 2, '0', STR_PAD_LEFT) : '01';

        return response()->json(['data' => "$code-$nextNumber"]);
    }
}

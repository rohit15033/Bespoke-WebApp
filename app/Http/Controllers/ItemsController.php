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
        //


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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Items $items)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Items $items)
    {
        //
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

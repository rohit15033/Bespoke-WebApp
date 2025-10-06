<?php

namespace App\Http\Controllers;

use App\Models\Headwear;
use Illuminate\Http\Request;

class HeadwearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $payload = [];
        $headwearList = Headwear::getHeadwearList($payload);
        return response()->json($headwearList);
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
    public function show(Headwear $headwear)
    {
        //
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
    public function update(Request $request, Headwear $headwear)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Headwear $headwear)
    {
        //
    }
}

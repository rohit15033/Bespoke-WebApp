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
         $payload = [
            'beskap_filter' => $request->input('beskap_filter', null), // Optional filter parameter 
            'color' => $request->input('color', null), // Optional filter parameter  
            'subcolor' => $request->input('subcolor', null), // Optional filter parameter
            'page' => $request->input('page', 1), // Default to page 1 if not provided
            'limit' => $request->input('limit', 10), // Default to 5 items per page if not provided
            'sort' => $request->input('sort', 'created_at'), // 
        ];

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
}

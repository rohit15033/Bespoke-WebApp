<?php

namespace App\Http\Controllers;

use App\Models\PackageBlueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PackageBlueprint::query();

        $packages = $query->get();

        return response()->json($packages);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $order = PackageBlueprint::with([
            'setBlueprints.itemBlueprints.itemTypes',
        ])->findOrFail($id);

        return response()->json($order);
    }
}

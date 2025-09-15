<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubColors;

class SubcolorsController extends Controller
{
    //
    public function index()
    {
        $subcolors = SubColors::all()->map(
          function ($subcolor) {
                return [
                    'id' => $subcolor->id,
                    'name' => $subcolor->name,
                ];
            }   
        );
        return response()->json([
            'message' => 'Subcolors list retrieved successfully',
            'data' => $subcolors,
        ], 200); // 200 = OK
    }   

    public function subColorsbyColorId($colorId){
        $subcolors = SubColors::getSubcolorsByColorId($colorId);
        return response()->json([
            'message' => 'Subcolors by color ID retrieved successfully',
            'data' => $subcolors,
        ], 200); // 200 = OK
    }   

}

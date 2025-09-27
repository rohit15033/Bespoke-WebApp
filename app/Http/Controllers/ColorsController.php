<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colors;  
use App\Models\SubColors;  


class ColorsController extends Controller
{
    public function index()
    {
        $colors = Colors::colorsList();
         return response()->json([
            'message' => 'Colors list retrieved successfully',
            'data' => $colors,
        ], 200); // 200 = OK
    }


}

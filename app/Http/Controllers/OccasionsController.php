<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Occasions;

class OccasionsController extends Controller
{
    public function index()
    {
        $occasions = Occasions::occasionsList();
        return response()->json([
            'message' => 'Occasions list retrieved successfully',
            'data' => $occasions,
        ], 200); // 200 = OK
    }   
}

<?php

namespace App\Http\Controllers;
use App\Models\Beskap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Validation\ValidationException;

class BeskapController extends Controller
{
    //
    public function index(Request $request){
        $payload = [
            'beskap_filter' => $request->input('beskap_filter', null), // Optional filter parameter 
            'color' => $request->input('color', null), // Optional filter parameter  
            'subcolor' => $request->input('subcolor', null), // Optional filter parameter
            'page' => $request->input('page', 1), // Default to page 1 if not provided
            'limit' => $request->input('limit', 10), // Default to 5 items per page if not provided
            'sort' => $request->input('sort', 'created_at'), // 
        ];
        $beskapList = Beskap::getBeskapList($payload);
        return response()->json([
            'message' => "beskap list has been retrieved",
            'beskap' => $beskapList
        ]);
        
    }

    public function store(Request $request){
            
        
    }

    public function show($id){

    }
    public function update(Request $request, $id){

    }
    public function destroy($id){

    }
}

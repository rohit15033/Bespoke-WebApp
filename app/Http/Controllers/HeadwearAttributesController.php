<?php

namespace App\Http\Controllers;


use App\Models\HeadwearAttributes;
use Illuminate\Http\Request;

class HeadwearAttributesController extends Controller
{
    //

    public function index()
    {
        //
        $attributes = HeadwearAttributes::getAllAttributes();
        return response()->json($attributes);
    }

    public function attributesWithValues()
    {
        $attributes = HeadwearAttributes::getAllAttributesWithValues();
        return response()->json($attributes);
    }
}

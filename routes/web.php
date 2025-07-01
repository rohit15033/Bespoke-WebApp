<?php

use App\Http\Controllers\AppointmentsController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;


Route::get('/', function () {
});

// Route::resource('appointments', AppointmentsController::class);


// Route::inertia('/signin','Auth/Signin');


Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});
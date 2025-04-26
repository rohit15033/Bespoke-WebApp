<?php

use App\Http\Controllers\AppointmentsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;


Route::get('/', function () {
    return Inertia::render('Home', ['name' => 'World']);
});

Route::resource('appointments', AppointmentsController::class);


Route::inertia('/signin','Auth/Signin');


Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});
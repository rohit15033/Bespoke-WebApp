<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', ['name' => 'World']);
});

Route::resource('appointment', AppointmentController::class);


Route::inertia('/signin','Auth/Signin');


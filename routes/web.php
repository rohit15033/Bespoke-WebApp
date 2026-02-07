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

Route::get('/contact-{platform?}', [\App\Http\Controllers\LeadTrackingController::class, 'contactRedirect'])->name('lead.contact.platform');
Route::get('/contact', [\App\Http\Controllers\LeadTrackingController::class, 'contactRedirect'])->name('lead.contact');
Route::get('/api/redirect-links', [\App\Http\Controllers\LeadTrackingController::class, 'getRedirectLinks']);
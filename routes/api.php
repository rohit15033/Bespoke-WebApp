<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentsController; // <- make sure this matches your controller
use App\Models\Appointments;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and assigned the "api"
| middleware group. Enjoy building your API!
|
*/

// Example basic route
// Route::apiResource('appointments', AppointmentsController::class);
//Appointment Route
Route::get('/appointments', [AppointmentsController::class, 'index']);
Route::post('/appointments', [AppointmentsController::class, 'create']);
Route::get('/appointments/count', [AppointmentsController::class, 'count']);
Route::get('/appointments/{id}', [AppointmentsController::class, 'get']);
Route::patch('/appointments/{id}', [AppointmentsController::class, 'update']);
Route::delete('/appointments/{id}', [AppointmentsController::class, 'destroy']);

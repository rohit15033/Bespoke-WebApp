<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BeskapController;
use App\Http\Controllers\CelanaController;
use App\Http\Controllers\KebayaController;
use App\Http\Controllers\SelopController;


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

//route for colors and occasions later n for inventory
Route::get('/colors', [App\Http\Controllers\ColorsController::class, 'index']);
Route::get('/subcolors', [App\Http\Controllers\SubcolorsController::class, 'index']);
Route::get('/occasions', [App\Http\Controllers\OccasionsController::class, 'index']);
Route::get('/subcolors/{colorId}', [App\Http\Controllers\SubcolorsController::class, 'subColorsbyColorId']);


Route::post('/auth/login', [AuthController::class, 'login']);
//kebaya routes
Route::get('kebaya', [KebayaController::class, 'index']);
Route::post('kebaya', [KebayaController::class, 'store']);
Route::get('kebaya/{id}', [KebayaController::class, 'show']);
Route::patch('kebaya/{id}', [KebayaController::class, 'update']);
Route::delete('kebaya/{id}', [KebayaController::class, 'destroy']);

//beskap routes
Route::get('beskap', [BeskapController::class, 'index']);
Route::post('beskap', [BeskapController::class, 'store']);
Route::get('beskap/{id}', [BeskapController::class, 'show']);
Route::patch('beskap/{id}', [BeskapController::class, 'update']);
Route::delete('beskap/{id}', [BeskapController::class, 'destroy']);

//celana routes
Route::get('celana', [CelanaController::class, 'index']);
Route::post('celana', [CelanaController::class, 'store']);
Route::get('celana/{id}', [CelanaController::class, 'show']);
Route::patch('celana/{id}', [CelanaController::class, 'update']);
Route::delete('celana/{id}', [CelanaController::class, 'destroy']);

//selop routes
Route::get('selop', [SelopController::class, 'index']);
Route::post('selop', [SelopController::class, 'store']);
Route::get('selop/{id}', [SelopController::class, 'show']);
Route::patch('selop/{id}', [SelopController::class, 'update']);
Route::delete('selop/{id}', [SelopController::class, 'destroy']);



// Protected routes (require authentication with Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/users/me', [AuthController::class, 'user']);

    Route::get('/appointments', [AppointmentsController::class, 'index']);
    Route::post('/appointments', [AppointmentsController::class, 'create']);
    Route::get('/appointments/count', [AppointmentsController::class, 'count']);
    Route::get('/appointments/{id}', [AppointmentsController::class, 'get']);
    Route::patch('/appointments/{id}', [AppointmentsController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentsController::class, 'destroy']);

    // Order routes
    Route::get('/orders/create', [OrderController::class, 'create']);
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit']);
    Route::apiResource('orders', OrderController::class);

    // Package routes
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{id}', [PackageController::class, 'show']);

    // TODO remove this after new items endpoint are made
    // Item routes
    Route::get('/items', [ItemController::class, 'index']);
    Route::get('/items/{id}', [ItemController::class, 'show']);

    //kebaya route
});

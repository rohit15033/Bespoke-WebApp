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
use App\Http\Controllers\BustierController;
use App\Http\Controllers\MansetController;
use App\Http\Controllers\HijabController;
use App\Http\Controllers\VeilController;
use App\Http\Controllers\EkorController;
use App\Http\Controllers\VestController;
use App\Http\Controllers\DasiController;
use App\Http\Controllers\KemejaController;
use App\Http\Controllers\HeadwearController;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\ItemsController;


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

//bustier routes
Route::get('bustier', [BustierController::class, 'index']);
Route::post('bustier', [BustierController::class, 'store']);
Route::get('bustier/{id}', [BustierController::class, 'show']);
Route::patch('bustier/{id}', [BustierController::class, 'update']);
Route::delete('bustier/{id}', [BustierController::class, 'destroy']);

//manset routes
Route::get('manset', [MansetController::class, 'index']);
Route::post('manset', [MansetController::class, 'store']);
Route::get('manset/{id}', [MansetController::class, 'show']);
Route::patch('manset/{id}', [MansetController::class, 'update']);
Route::delete('manset/{id}', [MansetController::class, 'destroy']);

//hijab routes
Route::get('hijab', [HijabController::class, 'index']);
Route::post('hijab', [HijabController::class, 'store']);
Route::get('hijab/{id}', [HijabController::class, 'show']);
Route::patch('hijab/{id}', [HijabController::class, 'update']);
Route::delete('hijab/{id}', [HijabController::class, 'destroy']);

//veil routes
Route::get('veil', [VeilController::class, 'index']);
Route::post('veil', [VeilController::class, 'store']);
Route::get('veil/{id}', [VeilController::class, 'show']);
Route::patch('veil/{id}', [VeilController::class, 'update']);
Route::delete('veil/{id}', [VeilController::class, 'destroy']);

//ekor routes
Route::get('ekor', [EkorController::class, 'index']);
Route::post('ekor', [EkorController::class, 'store']);
Route::get('ekor/{id}', [EkorController::class, 'show']);
Route::patch('ekor/{id}', [EkorController::class, 'update']);
Route::delete('ekor/{id}', [EkorController::class, 'destroy']);

//vest routes
Route::get('vest', [VestController::class, 'index']);
Route::post('vest', [VestController::class, 'store']);
Route::get('vest/{id}', [VestController::class, 'show']);
Route::patch('vest/{id}', [VestController::class, 'update']);
Route::delete('vest/{id}', [VestController::class, 'destroy']);

//dasi routes
Route::get('dasi', [DasiController::class, 'index']);
Route::post('dasi', [DasiController::class, 'store']);
Route::get('dasi/{id}', [DasiController::class, 'show']);
Route::patch('dasi/{id}', [DasiController::class, 'update']);
Route::delete('dasi/{id}', [DasiController::class, 'destroy']);

//kemeja routes 
Route::get('kemeja', [KemejaController::class, 'index']);
Route::post('kemeja', [KemejaController::class, 'store']);
Route::get('kemeja/{id}', [KemejaController::class, 'show']);
Route::patch('kemeja/{id}', [KemejaController::class, 'update']);
Route::delete('kemeja/{id}', [KemejaController::class, 'destroy']);

//headwear routes
Route::get('headwear', [HeadwearController::class, 'index']);
Route::post('headwear', [HeadwearController::class, 'store']);
Route::get('headwear/{id}', [HeadwearController::class, 'show']);
Route::patch('headwear/{id}', [HeadwearController::class, 'update']);
Route::delete('headwear/{id}', [HeadwearController::class, 'destroy']);

//accessories routes
Route::get('accessories', [AccessoryController::class, 'index']);
Route::post('accessories', [AccessoryController::class, 'store']);
Route::get('accessories/{id}', [AccessoryController::class, 'show']);
Route::patch('accessories/{id}', [AccessoryController::class, 'update']);
Route::delete('accessories/{id}', [AccessoryController::class, 'destroy']);

//items
Route::get('get-item-code', [ItemsController::class, 'getItemCode']);


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

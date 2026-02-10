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
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\LeadTrackingController; // Added for frontend redirect logging

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

// WhatsApp Webhook
Route::match(['get', 'post'], '/webhooks/whatsapp', [WhatsAppController::class, 'webhook']);

// Social Posts (Content Analytics)
Route::resource('social-posts', App\Http\Controllers\SocialPostController::class);
Route::post('social-posts/sync', [App\Http\Controllers\SocialPostController::class, 'sync']);

// Logs an intent from the frontend without redirecting (since frontend handles it)
Route::post('/log-lead-intent', [LeadTrackingController::class, 'logIntent'])->name('api.lead.intent.log');

//route for colors and occasions later n for inventory
Route::get('/colors', [App\Http\Controllers\ColorsController::class, 'index']);
Route::get('/subcolors', [App\Http\Controllers\SubcolorsController::class, 'index']);
Route::get('/occasions', [App\Http\Controllers\OccasionsController::class, 'index']);
Route::get('/subcolors/{colorId}', [App\Http\Controllers\SubcolorsController::class, 'subColorsbyColorId']);

// Public login endpoint with rate limiting
Route::middleware('throttle:10,1')->post('/auth/login', [AuthController::class, 'login']);

// Public READ-ONLY inventory routes (for berkatkebaya.com public site)
// GET requests only - viewing inventory
Route::get('kebaya', [KebayaController::class, 'index']);
Route::get('kebaya/{id}', [KebayaController::class, 'show']);

Route::get('beskap', [BeskapController::class, 'index']);
Route::get('beskap/{id}', [BeskapController::class, 'show']);

Route::get('celana', [CelanaController::class, 'index']);
Route::get('celana/{id}', [CelanaController::class, 'show']);

Route::get('selop', [SelopController::class, 'index']);
Route::get('selop/{id}', [SelopController::class, 'show']);

Route::get('bustier', [BustierController::class, 'index']);
Route::get('bustier/{id}', [BustierController::class, 'show']);

Route::get('manset', [MansetController::class, 'index']);
Route::get('manset/{id}', [MansetController::class, 'show']);

Route::get('hijab', [HijabController::class, 'index']);
Route::get('hijab/{id}', [HijabController::class, 'show']);

Route::get('veil', [VeilController::class, 'index']);
Route::get('veil/{id}', [VeilController::class, 'show']);

Route::get('ekor', [EkorController::class, 'index']);
Route::get('ekor/{id}', [EkorController::class, 'show']);

Route::get('vest', [VestController::class, 'index']);
Route::get('vest/{id}', [VestController::class, 'show']);

Route::get('dasi', [DasiController::class, 'index']);
Route::get('dasi/{id}', [DasiController::class, 'show']);

Route::get('kemeja', [KemejaController::class, 'index']);
Route::get('kemeja/{id}', [KemejaController::class, 'show']);

Route::get('headwear', [HeadwearController::class, 'index']);
Route::get('headwear/{id}', [HeadwearController::class, 'show']);

Route::get('accessories', [AccessoryController::class, 'index']);
Route::get('accessories/{id}', [AccessoryController::class, 'show']);

//items
Route::get('get-item-code', [ItemsController::class, 'getItemCode']);


// Protected routes (require authentication with Sanctum token)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/users/me', [AuthController::class, 'user']);

    // Dashboard (protected)
    Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'stats']);
    Route::get('/dashboard/impact-analysis', [App\Http\Controllers\DashboardController::class, 'impactAnalysis']);

    // Inventory WRITE operations (protected - admin only)
    Route::post('kebaya', [KebayaController::class, 'store']);
    Route::patch('kebaya/{id}', [KebayaController::class, 'update']);
    Route::delete('kebaya/{id}', [KebayaController::class, 'destroy']);

    Route::post('beskap', [BeskapController::class, 'store']);
    Route::patch('beskap/{id}', [BeskapController::class, 'update']);
    Route::delete('beskap/{id}', [BeskapController::class, 'destroy']);

    Route::post('celana', [CelanaController::class, 'store']);
    Route::patch('celana/{id}', [CelanaController::class, 'update']);
    Route::delete('celana/{id}', [CelanaController::class, 'destroy']);

    Route::post('selop', [SelopController::class, 'store']);
    Route::patch('selop/{id}', [SelopController::class, 'update']);
    Route::delete('selop/{id}', [SelopController::class, 'destroy']);

    Route::post('bustier', [BustierController::class, 'store']);
    Route::patch('bustier/{id}', [BustierController::class, 'update']);
    Route::delete('bustier/{id}', [BustierController::class, 'destroy']);

    Route::post('manset', [MansetController::class, 'store']);
    Route::patch('manset/{id}', [MansetController::class, 'update']);
    Route::delete('manset/{id}', [MansetController::class, 'destroy']);

    Route::post('hijab', [HijabController::class, 'store']);
    Route::patch('hijab/{id}', [HijabController::class, 'update']);
    Route::delete('hijab/{id}', [HijabController::class, 'destroy']);

    Route::post('veil', [VeilController::class, 'store']);
    Route::patch('veil/{id}', [VeilController::class, 'update']);
    Route::delete('veil/{id}', [VeilController::class, 'destroy']);

    Route::post('ekor', [EkorController::class, 'store']);
    Route::patch('ekor/{id}', [EkorController::class, 'update']);
    Route::delete('ekor/{id}', [EkorController::class, 'destroy']);

    Route::post('vest', [VestController::class, 'store']);
    Route::patch('vest/{id}', [VestController::class, 'update']);
    Route::delete('vest/{id}', [VestController::class, 'destroy']);

    Route::post('dasi', [DasiController::class, 'store']);
    Route::patch('dasi/{id}', [DasiController::class, 'update']);
    Route::delete('dasi/{id}', [DasiController::class, 'destroy']);

    Route::post('kemeja', [KemejaController::class, 'store']);
    Route::patch('kemeja/{id}', [KemejaController::class, 'update']);
    Route::delete('kemeja/{id}', [KemejaController::class, 'destroy']);

    Route::post('headwear', [HeadwearController::class, 'store']);
    Route::patch('headwear/{id}', [HeadwearController::class, 'update']);
    Route::delete('headwear/{id}', [HeadwearController::class, 'destroy']);

    Route::post('accessories', [AccessoryController::class, 'store']);
    Route::patch('accessories/{id}', [AccessoryController::class, 'update']);
    Route::delete('accessories/{id}', [AccessoryController::class, 'destroy']);

    Route::get('/appointments', [AppointmentsController::class, 'index']);
    Route::post('/appointments', [AppointmentsController::class, 'store']);
    Route::get('/appointments/count', [AppointmentsController::class, 'count']);
    Route::get('/appointments/{id}', [AppointmentsController::class, 'show']);
    Route::patch('/appointments/{id}', [AppointmentsController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentsController::class, 'destroy']);

    // Order routes
    Route::get('customers/analytics/status', [CustomerController::class, 'analyticsStatus']);
    Route::get('customers/index-with-status', [CustomerController::class, 'indexWithStatus']);
    Route::get('customers/{customer}/history', [CustomerController::class, 'history']);
    Route::apiResource('customers', \App\Http\Controllers\CustomerController::class);
    Route::apiResource('orders', OrderController::class);
    Route::get('/events', [OrderController::class, 'events']);

    // Package routes
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{id}', [PackageController::class, 'show']);

    // Items routes
    Route::apiResource('/items', ItemsController::class);

    // Payment routes
    Route::get('/payments', [App\Http\Controllers\PaymentRecordController::class, 'index']);
    Route::post('/payments', [App\Http\Controllers\PaymentRecordController::class, 'store']);
    Route::delete('/payments/{id}', [App\Http\Controllers\PaymentRecordController::class, 'destroy']);

    Route::get('/daily-reports', [App\Http\Controllers\DailyReportController::class, 'index']);
    Route::post('/daily-reports', [App\Http\Controllers\DailyReportController::class, 'store']);

    Route::get('/attendance/status', [App\Http\Controllers\AttendanceController::class, 'status']);
    Route::get('/attendance/summary', [App\Http\Controllers\AttendanceController::class, 'summary']);
    Route::get('/attendance/history', [App\Http\Controllers\AttendanceController::class, 'history']);
    Route::post('/attendance/clock-in', [App\Http\Controllers\AttendanceController::class, 'clockIn']);
    Route::post('/attendance/clock-out', [App\Http\Controllers\AttendanceController::class, 'clockOut']);
    Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store']); // Manual Create
    Route::put('/attendance/{id}', [App\Http\Controllers\AttendanceController::class, 'update']); // Manual Update

    Route::apiResource('absence', App\Http\Controllers\AbsenceController::class);

    // Master-only routes
    Route::get('salespeople', [UserController::class, 'salespeople']);
    Route::apiResource('users', UserController::class);
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
});

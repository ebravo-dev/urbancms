<?php

use App\Http\Controllers\Api\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Apply CORS and rate limiting middleware to all API routes
Route::middleware([
    \App\Http\Middleware\CorsMiddleware::class,
    \App\Http\Middleware\ApiRateLimitMiddleware::class
])->group(function () {

    // To use API token security, uncomment this group and comment out the public routes below
    /*
    Route::middleware(\App\Http\Middleware\ApiTokenMiddleware::class)->group(function () {
        Route::get('/properties', [PropertyController::class, 'index']);
        Route::get('/properties/{property}', [PropertyController::class, 'show']);
    });
    */

    // Public API Endpoints (Comment these out if using API token security)
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{property}', [PropertyController::class, 'show']);

    // Test endpoint to check API connectivity
    Route::get('/ping', function () {
        return response()->json([
            'message' => 'pong',
            'status' => 'success',
            'timestamp' => now()->toIso8601String()
        ]);
    });
});

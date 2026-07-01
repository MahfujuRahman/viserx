<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// ==========================================
// Public Authentication Routes
// ==========================================
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// ==========================================
// Protected Routes (Requires Valid Access Token)
// ==========================================
Route::middleware('auth:api')->group(function () {

    // User Session
    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    // API Resource: Categories
    Route::apiResource('categories', CategoryController::class);

    // API Resource: Products
    Route::apiResource('products', ProductController::class);

});

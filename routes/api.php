<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Middleware\AdminMiddleware;

// Public Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Protected Auth Routes
Route::prefix('auth')->middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Protected Admin API Routes
Route::middleware(['auth:api', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::get('/pages', [PageSectionController::class, 'index']);
    Route::put('/pages/{page}', [PageSectionController::class, 'update']);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::post('/orders/{id}/payment-status', [OrderController::class, 'updatePaymentStatus']);
});

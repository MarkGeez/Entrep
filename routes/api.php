<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);

// User routes (role = user)
Route::middleware(['auth:sanctum', 'role:user'])->prefix('user')->group(function () {

    // Account
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Check if a car is free for the chosen dates
    Route::get('/vehicles/{vehicle}/availability', [VehicleController::class, 'availability']);

    // Rentals
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::post('/rentals', [RentalController::class, 'store']);
    Route::get('/rentals/{rental}', [RentalController::class, 'show']);
    Route::patch('/rentals/{rental}/cancel', [RentalController::class, 'cancel']);

    // Simulated payment
    Route::post('/rentals/{rental}/pay', [PaymentController::class, 'pay']);

    // Reviews (only after the rental is completed)
    Route::post('/rentals/{rental}/review', [ReviewController::class, 'store']);
});
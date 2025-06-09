<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // Ensure this is App\Http\Controllers\Api\AuthController
use App\Http\Controllers\Api\PartController as ApiPartController;
use App\Http\Controllers\Api\TransactionController as ApiTransactionController;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // This route MUST be here
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Parts API
    Route::get('/parts/search', [ApiPartController::class, 'search']);
    Route::get('/parts/by-barcode/{barcode}', [ApiPartController::class, 'getPartByBarcode']);

    // Transaction API
    Route::get('/transactions/temp', [ApiTransactionController::class, 'getTempTransactions']);
    Route::post('/transactions/add-to-cart', [ApiTransactionController::class, 'addToCart']);
    Route::patch('/transactions/update-cart-quantity/{tempTransactionId}', [ApiTransactionController::class, 'updateCartQuantity']);
    Route::delete('/transactions/remove-from-cart/{tempTransactionId}', [ApiTransactionController::class, 'removeFromCart']);
    Route::post('/transactions/process', [ApiTransactionController::class, 'processTransaction']);
    Route::get('/transactions/history', [ApiTransactionController::class, 'getHistory']);
    Route::get('/transactions/{transactionId}', [ApiTransactionController::class, 'show']);
});
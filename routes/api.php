<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PartController as ApiPartController;
use App\Http\Controllers\Api\TransactionController as ApiTransactionController;

// Pastikan TIDAK ADA DEFINISI RateLimiter::for() di sini.
// Mereka seharusnya di App\Providers\AppServiceProvider.php

// ----------------------------------------------------------------------
// Public routes (no authentication required)
// Rute-rute ini dapat diakses oleh siapa saja tanpa token.
// ----------------------------------------------------------------------
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);

// PASTIKAN KEDUA BARIS INI ADA PERSIS SEPERTI INI:
Route::get('/parts/search', [App\Http\Controllers\Api\PartController::class, 'search']);
Route::get('/parts/by-barcode/{barcode}', [App\Http\Controllers\Api\PartController::class, 'getPartByBarcode']);


// ----------------------------------------------------------------------
// Authenticated routes (require Sanctum token)
// Rute-rute di dalam grup ini MEMBUTUHKAN token Sanctum yang valid.
// ----------------------------------------------------------------------
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rute-rute parts yang membutuhkan autentikasi (jika ada, seperti menambah/mengubah/menghapus)
    // Jika semua operasi parts hanya berupa pencarian public, maka tidak perlu ada rute parts di sini.

    // Transaction API (ini harus tetap di sini karena sensitif)
    Route::get('/transactions/temp', [ApiTransactionController::class, 'getTempTransactions']);
    Route::post('/transactions/add-to-cart', [ApiTransactionController::class, 'addToCart']);
    Route::patch('/transactions/update-cart-quantity/{tempTransactionId}', [ApiTransactionController::class, 'updateCartQuantity']);
    Route::delete('/transactions/remove-from-cart/{tempTransactionId}', [ApiTransactionController::class, 'removeFromCart']);
    Route::post('/transactions/process', [ApiTransactionController::class, 'processTransaction']);
    Route::get('/transactions/history', [ApiTransactionController::class, 'getHistory']);
    Route::get('/transactions/{transactionId}', [ApiTransactionController::class, 'show']);
});
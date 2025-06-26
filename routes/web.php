<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\CompatibleController;
use App\Http\Controllers\PartBarcodeController;
use App\Http\Controllers\TransactionController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Routes untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('categories', CategoryController::class);
    // Route::resource('parts', PartController::class);
    Route::resource('compatibles', CompatibleController::class);
    Route::resource('part-barcodes', PartBarcodeController::class); // For CRUD of barcodes

    // Transaction routes
    Route::get('/transactions/checkout', [TransactionController::class, 'checkout'])->name('transactions.checkout');
    Route::post('/transactions/add-to-cart', [TransactionController::class, 'addToCart'])->name('transactions.add_to_cart');
    Route::patch('/transactions/update-cart-quantity/{tempTransaction}', [TransactionController::class, 'updateCartQuantity'])->name('transactions.update_cart_quantity');
    Route::delete('/transactions/remove-from-cart/{tempTransaction}', [TransactionController::class, 'removeFromCart'])->name('transactions.remove_from_cart');
    Route::post('/transactions/process', [TransactionController::class, 'processTransaction'])->name('transactions.process');
    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

   

    //route pada parts
    Route::resource('parts', PartController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});
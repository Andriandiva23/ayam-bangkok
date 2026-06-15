<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AyamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;

// Halaman Utama & Auth
Route::get('/', [AyamController::class, 'katalog'])->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Admin & Karyawan
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AyamController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::resource('ayam', AyamController::class);
});

// Rute Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
});

// Midtrans Webhook (Tanpa Middleware Auth)
Route::post('/midtrans-callback', [CheckoutController::class, 'callback']);
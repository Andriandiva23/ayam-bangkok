<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AyamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController; // <-- Tambahan Controller untuk pesanan

// Halaman Utama & Auth
Route::get('/', [AyamController::class, 'katalog'])->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Admin & Karyawan
Route::middleware(['auth', 'role:admin,karyawan'])->group(function () {
    Route::get('/admin/dashboard', [AyamController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // --- Rute Manajemen Ayam ---
    Route::get('/admin/ayam', [AyamController::class, 'index'])->name('admin.ayam.index');
    Route::get('/admin/ayam/create', [AyamController::class, 'create'])->name('admin.ayam.create');
    Route::post('/admin/ayam/store', [AyamController::class, 'store'])->name('admin.ayam.store');
    
    // Rute Edit dan Hapus Ayam
    Route::get('/admin/ayam/{ayam}/edit', [AyamController::class, 'edit'])->name('admin.ayam.edit');
    Route::put('/admin/ayam/{ayam}', [AyamController::class, 'update'])->name('admin.ayam.update');
    Route::delete('/admin/ayam/{ayam}', [AyamController::class, 'destroy'])->name('admin.ayam.destroy');

    // --- Rute Pesanan Masuk ---
    Route::get('/admin/pesanan', [OrderController::class, 'index'])->name('admin.pesanan.index');
});

// Rute Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
});

// Midtrans Webhook (Tanpa Middleware Auth)
Route::post('/midtrans-callback', [CheckoutController::class, 'callback']);
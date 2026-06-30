<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AyamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;

// Rute Halaman Utama (Katalog) & Auth
Route::get('/', [AyamController::class, 'katalog'])->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Khusus Admin & Karyawan
Route::middleware(['auth', 'role:admin,karyawan'])->group(function () {
    Route::get('/admin/dashboard', [AyamController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::resource('admin/ayam', AyamController::class)->names('admin.ayam');
    
    // Rute Pesanan Masuk
    Route::get('/admin/pesanan', [CheckoutController::class, 'pesanan'])->name('admin.pesanan.index');
});

Route::middleware(['auth', 'role:admin,karyawan'])->group(function () {
    // ... rute lainnya
    Route::get('/admin/pesanan/export', [CheckoutController::class, 'exportExcel'])->name('admin.pesanan.export');
});

// Rute Khusus Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/checkout/form', [CheckoutController::class, 'form'])->name('checkout.form'); 
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
});

// Rute Webhook Midtrans
Route::post('/midtrans-callback', [CheckoutController::class, 'callback']);

Route::post('/admin/pesanan/kirim/{id}', [CheckoutController::class, 'updatePengiriman'])->name('admin.pesanan.kirim');
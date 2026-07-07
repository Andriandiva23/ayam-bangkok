<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AyamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EkspedisiController;
use App\Http\Controllers\KategoriAyamController;

// Rute Halaman Utama (Katalog) & Auth
Route::get('/', [AyamController::class, 'katalog'])->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Khusus Admin & Karyawan
Route::middleware(['auth', 'role:admin,karyawan', 'prevent-direct'])->group(function () {
    Route::get('/admin/dashboard', [AyamController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::resource('admin/ayam', AyamController::class)->names('admin.ayam');
    
    // Rute Pesanan Masuk & Selesai
    Route::get('/admin/pesanan', [CheckoutController::class, 'pesanan'])->name('admin.pesanan.index');
    Route::get('/admin/pesanan-selesai', [CheckoutController::class, 'pesananSelesai'])->name('admin.pesanan.selesai');
    Route::get('/admin/pesanan/sync-midtrans/{id}', [CheckoutController::class, 'syncMidtrans'])->name('admin.pesanan.sync_midtrans');
});

Route::middleware(['auth', 'role:admin,karyawan', 'prevent-direct'])->group(function () {
    // ... rute lainnya
    Route::get('/admin/pesanan/export', [CheckoutController::class, 'exportExcel'])->name('admin.pesanan.export');
});

Route::middleware(['auth', 'role:admin,karyawan', 'prevent-direct'])->group(function () {
    // ... rute dashboard, ayam, pesanan yang sudah ada ...
    
    // CRUD Layanan Ekspedisi
    Route::resource('admin/ekspedisi', EkspedisiController::class)->names('admin.ekspedisi');
    
    // CRUD Manajemen Pelanggan (Hanya index, show, destroy)
    Route::resource('admin/pelanggan', \App\Http\Controllers\PelangganController::class)->only(['index', 'show', 'destroy'])->names('admin.pelanggan');
});

// CRUD Kategori Ayam
Route::middleware(['auth', 'role:admin,karyawan', 'prevent-direct'])->group(function () {
    Route::resource('admin/kategori', KategoriAyamController::class)->names('admin.kategori');
});

// Rute Khusus Pelanggan (Bisa diakses Admin/Karyawan untuk input manual)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/form', [CheckoutController::class, 'form'])->name('checkout.form'); 
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'customerSyncMidtrans'])->name('checkout.success');
});

// Rute Webhook Midtrans
Route::post('/midtrans-callback', [CheckoutController::class, 'callback']);

Route::post('/admin/pesanan/kirim/{id}', [CheckoutController::class, 'updatePengiriman'])->name('admin.pesanan.kirim');
Route::post('/admin/pesanan/bayar-cod/{id}', [CheckoutController::class, 'bayarCod'])->name('admin.pesanan.bayar_cod');

// Rute Hapus Pesanan (Khusus Admin)
Route::delete('/admin/pesanan/{id}', [CheckoutController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pesanan.destroy');
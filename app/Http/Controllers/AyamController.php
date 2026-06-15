<?php

namespace App\Http\Controllers;

use App\Models\Ayam;
use App\Models\Order;
use Illuminate\Http\Request;

class AyamController extends Controller
{
    // Tampilan Pelanggan
    public function katalog() {
        $ayams = Ayam::all();
        return view('pelanggan.katalog', compact('ayams'));
    }

    // Tampilan Admin/Karyawan
    public function adminDashboard() {
        // Data untuk tabel atau keperluan lain di dashboard
        $ayams = Ayam::all();
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        // 1. Menghitung Total Penjualan 
        $totalPenjualan = Order::where('status', 'selesai')->sum('total_harga'); 

        // 2. Menghitung jumlah pesanan yang sudah selesai
        $pesananSelesai = Order::where('status', 'selesai')->count();

        // 3. PERBAIKAN: Menggunakan count() karena tidak ada kolom 'stok' di database.
        // Ini akan menghitung total seluruh baris ayam yang ada di tabel.
        $totalStokAyam = Ayam::count(); 

        /* Catatan: 
        Jika Anda sebenarnya memiliki kolom untuk jumlah ayam tetapi namanya bukan 'stok' 
        (misalnya bernama 'jumlah'), hapus baris di atas dan gunakan baris di bawah ini:
        $totalStokAyam = Ayam::sum('jumlah'); 
        */

        return view('admin.dashboard', compact(
            'ayams', 
            'orders', 
            'totalPenjualan', 
            'pesananSelesai', 
            'totalStokAyam'
        ));
    }

    // ... (fungsi index, create, store, edit, update, destroy tetap biarkan saja) ...
}
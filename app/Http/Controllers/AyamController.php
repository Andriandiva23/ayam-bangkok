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
        $ayams = Ayam::all();
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('ayams', 'orders'));
    }

    // ... (biarkan fungsi index, create, store, edit, update, destroy yang sudah Anda buat sebelumnya) ...
}
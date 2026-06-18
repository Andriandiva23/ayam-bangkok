<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Mengambil semua data pesanan dari yang terbaru
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.pesanan.index', compact('orders'));
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        // Mengambil semua data pesanan dari yang terbaru
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.pesanan.index', compact('orders'));
    }

    // --- Fungsi Baru: Memproses form dari keranjang pelanggan ---
    public function processCheckout(Request $request)
    {
        // 1. Validasi data yang dikirim dari form keranjang
        $request->validate([
            'nama_pembeli'      => 'required|string|max:255',
            'alamat_pembeli'    => 'required|string',
            'metode_pengiriman' => 'required|in:travel,cod',
            'total_harga'       => 'required|numeric',
            'cart_items'        => 'required' // Data JSON dari Alpine.js
        ]);

        // Cek apakah keranjang kosong
        $cartItems = json_decode($request->cart_items, true);
        if(empty($cartItems)) {
            return back()->with('error', 'Keranjang masih kosong!');
        }

        // 2. Simpan pesanan ke database
        $order = Order::create([
            'user_id'           => Auth::id(), // ID pelanggan yang sedang login
            'kode_order'        => 'ORD-' . strtoupper(Str::random(8)),
            'nama_pembeli'      => $request->nama_pembeli,
            'alamat_pembeli'    => $request->alamat_pembeli,
            'metode_pengiriman' => $request->metode_pengiriman,
            'total_harga'       => $request->total_harga,
            'status'            => 'pending',
            // Catatan: Jika Midtrans digenerate di Controller ini, 
            // simpan snap_token-nya di sini nanti.
        ]);

        // Opsional: Jika Anda memiliki tabel OrderDetail, Anda bisa melakukan perulangan (foreach)
        // pada variabel $cartItems dan menyimpannya di sini agar detail ayam yang dibeli tercatat.

        // 3. Arahkan pengguna ke halaman pembayaran (payment.blade.php)
        return view('payment', compact('order'));
    }
}
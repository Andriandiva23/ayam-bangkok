<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ayam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct() {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    // --- FUNGSI PRIVATE: Kirim WhatsApp ---
    private function kirimWhatsApp($no_hp, $pesan) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send', // Ganti dengan URL API Anda
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'target' => $no_hp,
                'message' => $pesan,
            ]),
            CURLOPT_HTTPHEADER => array('Authorization: YOUR_TOKEN_API'), // Ganti dengan Token Anda
        ));
        curl_exec($curl);
        curl_close($curl);
    }

    // --- FUNGSI ADMIN: Update Pengiriman ---
    public function updatePengiriman(Request $request, $id) {
        $order = Order::findOrFail($id);
        
        if ($order->status !== 'dibayar') {
            return back()->with('error', 'Pesanan belum dibayar!');
        }

        $order->update([
            'status_pengiriman' => 'dikirim',
            'nomor_resi' => $request->nomor_resi
        ]);

        // Notifikasi ke Pelanggan saat barang dikirim
        $pesan = "Halo {$order->nama_penerima}, pesanan {$order->kode_order} Anda telah dikirim dengan Resi: {$request->nomor_resi}. Terima kasih!";
        $this->kirimWhatsApp($order->no_hp, $pesan);

        return back()->with('success', 'Pesanan telah dikirim dan pelanggan sudah diberitahu via WhatsApp.');
    }

    // --- FUNGSI ADMIN: Daftar Pesanan ---
    public function pesanan() {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.pesanan.index', compact('orders'));
    }

    // --- FUNGSI PELANGGAN: Form & Process ---
    public function form(Request $request) {
        $ayam = Ayam::findOrFail($request->ayam_id);
        $qty = $request->qty;
        return view('pelanggan.checkout', compact('ayam', 'qty'));
    }

    public function process(Request $request) {
        $request->validate([
            'ayam_id' => 'required',
            'qty' => 'required|numeric',
            'nama_penerima' => 'required',
            'no_hp' => 'required',
            'alamat_lengkap' => 'required',
            'metode_pengiriman' => 'required'
        ]);
        
        $ayam = Ayam::findOrFail($request->ayam_id);
        if($ayam->stok < $request->qty) return back()->with('error', 'Stok tidak mencukupi!');

        $total_harga = $ayam->harga * $request->qty;
        $ayam->stok -= $request->qty;
        $ayam->save();

        $order = Order::create([
            'user_id' => Auth::id(),
            'kode_order' => 'ORD-' . strtoupper(Str::random(8)),
            'total_harga' => $total_harga,
            'status' => 'pending',
            'nama_penerima' => $request->nama_penerima,
            'no_hp' => $request->no_hp,
            'alamat_lengkap' => $request->alamat_lengkap,
            'metode_pengiriman' => $request->metode_pengiriman
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'ayam_id' => $ayam->id,
            'qty' => $request->qty,
            'harga' => $ayam->harga,
            'subtotal' => $total_harga
        ]);

        $params = [
            'transaction_details' => ['order_id' => $order->kode_order, 'gross_amount' => $total_harga],
            'customer_details' => ['first_name' => $request->nama_penerima, 'phone' => $request->no_hp],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $order->update(['snap_token' => $snapToken]);

        return redirect()->route('checkout.payment', $order->id);
    }

    public function payment(Order $order) {
        if($order->user_id != Auth::id()) abort(403);
        return view('pelanggan.payment', compact('order'));
    }

    public function callback(Request $request) {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        
        if($hashed == $request->signature_key){
            $order = Order::where('kode_order', $request->order_id)->first();
            if($request->transaction_status == 'capture' || $request->transaction_status == 'settlement'){
                $order->update(['status' => 'dibayar']);
                
                // Notifikasi WA sukses bayar
                $this->kirimWhatsApp($order->no_hp, "Pembayaran pesanan {$order->kode_order} telah diterima. Kami akan segera memproses pengiriman Anda.");
                $this->kirimWhatsApp('08123456789', "Pesanan baru {$order->kode_order} telah dibayar. Mohon segera diproses.");
            }
        }
    }
}
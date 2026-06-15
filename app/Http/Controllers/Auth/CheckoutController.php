<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ayam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct() {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function process(Request $request) {
        $ayam_id = $request->ayam_id;
        $qty = $request->qty;
        
        $ayam = Ayam::findOrFail($ayam_id);
        
        // Cek stok
        if($ayam->stok < $qty) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $total_harga = $ayam->harga * $qty;

        // Kurangi Stok
        $ayam->stok -= $qty;
        $ayam->save();

        // Buat Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'kode_order' => 'ORD-' . strtoupper(uniqid()),
            'total_harga' => $total_harga,
            'status' => 'pending'
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'ayam_id' => $ayam->id,
            'qty' => $qty,
            'harga' => $ayam->harga,
            'subtotal' => $total_harga
        ]);

        // Request Token Midtrans
        $params = array(
            'transaction_details' => array(
                'order_id' => $order->kode_order,
                'gross_amount' => $total_harga,
            ),
            'customer_details' => array(
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $order->update(['snap_token' => $snapToken]);

        return redirect()->route('checkout.payment', $order->id);
    }

    public function payment(Order $order) {
        // Pastikan hanya pemilik order yang bisa melihat
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
            }
        }
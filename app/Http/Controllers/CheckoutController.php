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
    private function kirimWhatsApp($no_hp, $pesan, $imagePath = null) {
        $data = [
            'target' => $no_hp,
            'message' => $pesan,
        ];
        
        if ($imagePath && file_exists($imagePath)) {
            $data['file'] = new \CURLFile($imagePath);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send', 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array('Authorization: ' . env('FONNTE_TOKEN')), 
        ));
        curl_exec($curl);
        curl_close($curl);
    }

    private function notifyAdminNewOrder($order, $isPaid = false) {
        $adminWa = env('ADMIN_WHATSAPP', '08123456789');
        
        $order->load(['orderDetails.ayam']);
        $detail = $order->orderDetails->first();
        $ayam = $detail ? $detail->ayam : null;
        
        // Gunakan file lokal untuk upload langsung ke Fonnte (Bisa dari Localhost)
        $imagePath = $ayam && $ayam->foto ? storage_path('app/public/' . $ayam->foto) : null;
        
        $statusBayar = $isPaid ? "✅ LUNAS (Midtrans)" : "⏳ MENUNGGU PEMBAYARAN (COD/Travel)";
        
        $pesan = "*PESANAN BARU MASUK!*\n\n";
        $pesan .= "Kode Order: {$order->kode_order}\n";
        $pesan .= "Status: {$statusBayar}\n";
        $pesan .= "Total Tagihan: Rp " . number_format($order->total_harga, 0, ',', '.') . "\n\n";
        
        $pesan .= "*Data Pelanggan:*\n";
        $pesan .= "Nama: {$order->nama_pembeli}\n";
        $pesan .= "No HP: {$order->no_hp}\n";
        $pesan .= "Alamat: {$order->alamat_pembeli}\n";
        $pesan .= "Pengiriman: " . strtoupper($order->metode_pengiriman) . "\n\n";
        
        if ($ayam) {
            $pesan .= "*Pesanan:*\n";
            foreach($order->orderDetails as $od) {
                $pesan .= "- {$od->ayam->nama_ayam} ({$od->qty} ekor)\n";
            }
        }
        
        $pesan .= "\nMohon segera diproses.";
        
        $this->kirimWhatsApp($adminWa, $pesan, $imagePath);
    }

    private function notifyCustomerNewOrder($order, $isPaid = false) {
        $order->load(['orderDetails.ayam']);
        $detail = $order->orderDetails->first();
        $ayam = $detail ? $detail->ayam : null;
        
        $imagePath = $ayam && $ayam->foto ? storage_path('app/public/' . $ayam->foto) : null;
        
        $statusBayar = $isPaid ? "✅ LUNAS (Midtrans)" : "⏳ MENUNGGU PEMBAYARAN";
        
        $pesan = "*BUKTI PESANAN ANDA*\n";
        $pesan .= "Terima kasih telah memesan di toko kami!\n\n";
        $pesan .= "Kode Order: {$order->kode_order}\n";
        $pesan .= "Status: {$statusBayar}\n";
        $pesan .= "Total Tagihan: Rp " . number_format($order->total_harga, 0, ',', '.') . "\n\n";
        
        $pesan .= "*Data Anda:*\n";
        $pesan .= "Nama: {$order->nama_pembeli}\n";
        $pesan .= "No HP: {$order->no_hp}\n";
        $pesan .= "Alamat: {$order->alamat_pembeli}\n";
        $pesan .= "Pengiriman: " . strtoupper($order->metode_pengiriman) . "\n\n";
        
        if ($ayam) {
            $pesan .= "*Rincian Pesanan:*\n";
            foreach($order->orderDetails as $od) {
                $pesan .= "- {$od->ayam->nama_ayam} ({$od->qty} ekor)\n";
            }
        }
        
        $pesan .= "\nPesanan Anda akan segera kami proses.";
        
        $this->kirimWhatsApp($order->no_hp, $pesan, $imagePath);
    }

    // --- FUNGSI ADMIN: Update Pengiriman ---
    public function updatePengiriman(Request $request, $id) {
        $order = Order::findOrFail($id);
        
        if ($order->status !== 'dibayar') {
            return back()->with('error', 'Pesanan belum dibayar!');
        }

        $ekspedisi = null;
        if ($request->filled('ekspedisi_id')) {
            $ekspedisi = \App\Models\Ekspedisi::find($request->ekspedisi_id);
        }

        $order->update([
            'status_pengiriman' => 'dikirim',
            'nomor_resi' => $request->nomor_resi
        ]);

        // Notifikasi ke Pelanggan saat barang dikirim
        $resiMsg = $request->nomor_resi ? " dengan No Wingbend/Pining: {$request->nomor_resi}" : "";
        $pesan = "*UPDATE PESANAN - JAGOFARM*\n\n";
        $pesan .= "Halo {$order->nama_pembeli},\n\n";
        $pesan .= "Pesanan Anda (Kode: {$order->kode_order}) *Telah Mulai Dikirim / Dalam Perjalanan*{$resiMsg}.\n\n";

        if ($ekspedisi) {
            $pesan .= "*Info Kurir / Travel:*\n";
            $pesan .= "Layanan: {$ekspedisi->nama_ekspedisi}\n";
            $pesan .= "No. HP / WA Kurir: {$ekspedisi->no_hp}\n\n";
            $pesan .= "Silakan hubungi nomor kurir/travel di atas untuk berkoordinasi mengenai lokasi pengantaran.\n\n";
        }

        $pesan .= "Terima kasih atas kepercayaannya berbelanja di JagoFarm!";
        
        $this->kirimWhatsApp($order->no_hp, $pesan);

        return back()->with('success', 'Pesanan telah diproses/dikirim dan notifikasi WA ke pelanggan berhasil terkirim beserta info kurir.');
    }

    // --- FUNGSI ADMIN: Terima Pembayaran COD ---
    public function bayarCod($id) {
        $order = Order::findOrFail($id);
        
        if ($order->metode_pengiriman !== 'cod') {
            return back()->with('error', 'Pesanan ini bukan metode COD!');
        }

        $order->update([
            'status' => 'dibayar',
            'status_pengiriman' => 'dikirim',
            'nomor_resi' => 'Diambil di Peternakan' // Atau bisa dikosongkan
        ]);
        
        // Notifikasi ke Pelanggan
        $pesan = "Pembayaran untuk pesanan {$order->kode_order} telah kami terima (Lunas COD). Kami akan segera menyiapkan pesanan Anda.";
        $this->kirimWhatsApp($order->no_hp, $pesan);

        return back()->with('success', 'Pembayaran COD berhasil dikonfirmasi.');
    }

    // --- FUNGSI ADMIN: Daftar Pesanan ---
    public function pesanan(Request $request) {
        $search = $request->input('search');

        $queryCOD = Order::with(['user', 'orderDetails.ayam' => function($q) { $q->withTrashed(); }])
                        ->where('metode_pengiriman', 'cod')
                        ->where(function($q) {
                            $q->where('status_pengiriman', '!=', 'dikirim')
                              ->orWhereNull('status_pengiriman');
                        });
        
        $queryTravel = Order::with(['user', 'orderDetails.ayam' => function($q) { $q->withTrashed(); }])
                        ->where('metode_pengiriman', '!=', 'cod')
                        ->where(function($q) {
                            $q->where('status_pengiriman', '!=', 'dikirim')
                              ->orWhereNull('status_pengiriman');
                        });

        if ($search) {
            $queryCOD->where(function($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                  ->orWhere('nama_pembeli', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });

            $queryTravel->where(function($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                  ->orWhere('nama_pembeli', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $ordersCOD = $queryCOD->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page_cod');
        $ordersTravel = $queryTravel->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page_travel');

        $ekspedisis = \App\Models\Ekspedisi::where('is_active', true)->get();
        $is_selesai = false;
        return view('admin.pesanan.index', compact('ordersCOD', 'ordersTravel', 'ekspedisis', 'search', 'is_selesai'));
    }

    // --- FUNGSI ADMIN: Daftar Pesanan Selesai ---
    public function pesananSelesai(Request $request) {
        $search = $request->input('search');

        $querySelesai = Order::with(['user', 'orderDetails.ayam' => function($q) { $q->withTrashed(); }])
                        ->where('status_pengiriman', 'dikirim');

        if ($search) {
            $querySelesai->where(function($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                  ->orWhere('nama_pembeli', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $orders = $querySelesai->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page');

        $ekspedisis = \App\Models\Ekspedisi::where('is_active', true)->get();
        $is_selesai = true;
        
        // Kita bisa me-reuse view yang sama, cukup kirim flag is_selesai
        return view('admin.pesanan.index', compact('orders', 'ekspedisis', 'search', 'is_selesai'));
    }

    // --- FUNGSI ADMIN: Hapus Pesanan ---
    public function destroy($id) {
        $order = Order::findOrFail($id);
        
        // Cek jika butuh menghapus relasi lain atau mengembalikan stok
        // Di sini kita biarkan order dihapus langsung, on delete cascade pada DB akan menghapus order_details
        $order->delete();

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }

    // --- FUNGSI PELANGGAN: Form & Process ---
    public function form(Request $request) {
        $ayam = Ayam::findOrFail($request->ayam_id);
        $qty = $request->qty;
        $ekspedisis = \App\Models\Ekspedisi::where('is_active', true)->get();
        return view('pelanggan.checkout', compact('ayam', 'qty', 'ekspedisis'));
    }

    public function process(Request $request) {
        $request->validate([
            'cart_items' => 'required',
            'nama_pembeli' => 'required',
            'no_hp' => 'required',
            'alamat_pembeli' => 'required',
            'metode_pengiriman' => 'required'
        ]);
        
        $cartItems = json_decode($request->cart_items, true);
        if (empty($cartItems)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        // Hitung total dan validasi stok
        $total_harga = 0;
        foreach ($cartItems as $item) {
            $ayam = Ayam::findOrFail($item['id']);
            if($ayam->stok < $item['qty']) {
                return back()->with('error', 'Stok ' . $ayam->nama_ayam . ' tidak mencukupi!');
            }
            $total_harga += $ayam->harga * $item['qty'];
        }

        // Cek apakah memilih Ekspedisi untuk menambah Ongkir Dasar
        $ekspedisi_id = null;
        $metode = $request->metode_pengiriman;
        if (str_starts_with($metode, 'Ekspedisi - ')) {
            $namaEks = str_replace('Ekspedisi - ', '', $metode);
            $ekspedisi = \App\Models\Ekspedisi::where('nama_ekspedisi', $namaEks)->first();
            if ($ekspedisi) {
                $total_harga += $ekspedisi->ongkir_dasar;
                $ekspedisi_id = $ekspedisi->id;
            }
        }

        $order = Order::create([
            'user_id' => Auth::id() ?? null, // Mengizinkan guest jika auth belum wajib
            'kode_order' => 'ORD-' . strtoupper(Str::random(8)),
            'total_harga' => $total_harga,
            'status' => 'pending',
            'nama_pembeli' => $request->nama_pembeli,
            'no_hp' => $request->no_hp,
            'alamat_pembeli' => $request->alamat_pembeli,
            'metode_pengiriman' => $request->metode_pengiriman,
            'ekspedisi_id' => $ekspedisi_id
        ]);

        // Buat detail dan kurangi stok
        foreach ($cartItems as $item) {
            $ayam = Ayam::findOrFail($item['id']);
            $subtotal = $ayam->harga * $item['qty'];
            
            OrderDetail::create([
                'order_id' => $order->id,
                'ayam_id' => $ayam->id,
                'qty' => $item['qty'],
                'harga' => $ayam->harga,
                'subtotal' => $subtotal
            ]);

            $ayam->stok -= $item['qty'];
            $ayam->save();
            
            if ($ayam->stok <= 0) {
                $ayam->delete();
            }
        }

        if (strtolower($request->metode_pengiriman) === 'cod') {
            $this->notifyAdminNewOrder($order, false);
            $this->notifyCustomerNewOrder($order, false);
            return redirect('/')->with('success', 'Pesanan COD berhasil dibuat! Silakan cek WhatsApp Anda untuk detail pesanan.');
        }

        $params = [
            'transaction_details' => ['order_id' => $order->kode_order, 'gross_amount' => $total_harga],
            'customer_details' => ['first_name' => $request->nama_pembeli, 'phone' => $request->no_hp],
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
                if ($order->status !== 'dibayar') {
                    $order->update(['status' => 'dibayar']);
                    
                    // Notifikasi WA lengkap ke Pelanggan
                    $this->notifyCustomerNewOrder($order, true);
                    
                    // Kirim notifikasi lengkap ke Admin beserta gambar
                    $this->notifyAdminNewOrder($order, true);
                }
            }
        }
    }

    // --- FUNGSI ADMIN: Sinkronisasi Status Midtrans (Jalan Pintas Localhost) ---
    public function syncMidtrans($id) {
        $order = Order::findOrFail($id);
        
        // Panggil API Midtrans untuk cek status asli
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $url = env('MIDTRANS_IS_PRODUCTION', false) 
            ? "https://api.midtrans.com/v2/{$order->kode_order}/status" 
            : "https://api.sandbox.midtrans.com/v2/{$order->kode_order}/status";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode($serverKey . ':'),
                'Accept: application/json'
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($response, true);

        if (isset($data['transaction_status']) && ($data['transaction_status'] == 'settlement' || $data['transaction_status'] == 'capture')) {
            $order->update(['status' => 'dibayar']);
            
            // Tembakkan notifikasi WA (Tetap dikirim untuk testing)
            $this->notifyCustomerNewOrder($order, true);
            $this->notifyAdminNewOrder($order, true);
            
            return back()->with('success', 'Sinkronisasi berhasil! Status menjadi Lunas & Notifikasi WA telah dikirim (atau dikirim ulang).');
        }

        return back()->with('error', 'Status di Midtrans belum dibayar atau transaksi dibatalkan.');
    }

    // --- FUNGSI PELANGGAN: Sinkronisasi Otomatis Setelah Bayar (Fallback Localhost) ---
    public function customerSyncMidtrans($id) {
        $order = Order::findOrFail($id);
        
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $url = env('MIDTRANS_IS_PRODUCTION', false) 
            ? "https://api.midtrans.com/v2/{$order->kode_order}/status" 
            : "https://api.sandbox.midtrans.com/v2/{$order->kode_order}/status";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode($serverKey . ':'),
                'Accept: application/json'
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($response, true);

        if (isset($data['transaction_status']) && ($data['transaction_status'] == 'settlement' || $data['transaction_status'] == 'capture')) {
            if ($order->status !== 'dibayar') {
                $order->update(['status' => 'dibayar']);
                
                // Tembakkan notifikasi WA
                $this->notifyCustomerNewOrder($order, true);
                $this->notifyAdminNewOrder($order, true);
            }
            return redirect('/')->with('success', 'Pembayaran Berhasil! Notifikasi WA telah dikirimkan.');
        }

        return redirect('/')->with('info', 'Pembayaran sedang diproses atau menunggu konfirmasi.');
    }
    // --- FUNGSI ADMIN: Export Pesanan ke Excel (.xls) Sesuai Format ---
    public function exportExcel()
    {
        // Ambil semua order, urutkan dari terlama atau terbaru (sesuai kebutuhan, kita pakai terbaru)
        $orders = Order::with(['orderDetails.ayam', 'user.orders'])->orderBy('created_at', 'desc')->get();
        
        $doneOrders = [];

        foreach ($orders as $order) {
            // Anggap pesanan selesai jika status utamanya 'selesai' ATAU status pengirimannya sudah 'dikirim'
            if (strtolower($order->status) === 'selesai' || strtolower($order->status_pengiriman) === 'dikirim') {
                $doneOrders[] = $order;
            }
        }

        $filename = "Laporan_Pesanan_JagoFarm_" . date('Y-m-d') . ".xls";
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
        // HEADER UTAMA
        echo '<tr>';
        echo '<th style="background-color:#d9e1f2; text-align:center;">KODE</th>';
        echo '<th style="background-color:#d9e1f2; text-align:center;">TANGGAL</th>';
        echo '<th style="background-color:#d9e1f2; text-align:center;">NAMA PELANGGAN</th>';
        echo '<th style="background-color:#d9e1f2; text-align:center;">TELEPON</th>';
        echo '<th style="background-color:#d9e1f2; text-align:center;">ALAMAT LENGKAP</th>';
        echo '<th style="background-color:#d9e1f2; text-align:center;">KATEGORI</th>';
        echo '<th style="background-color:#d9e1f2; text-align:center;">RINCIAN ITEM</th>';
        echo '<th style="background-color:#d9e1f2; text-align:center;">TOTAL HARGA</th>'; // Tambahan
        echo '</tr>';

        // Fungsi Render Baris Data
        $renderRow = function($order) {
            $pesananArr = [];
            foreach($order->orderDetails as $od) {
                $namaAyam = $od->ayam ? $od->ayam->nama_ayam : 'Ayam Dihapus';
                // Format RINCIAN ITEM: nama (qty)
                $pesananArr[] = "{$namaAyam} ({$od->qty})";
            }
            $pesanan = implode(', ', $pesananArr);
            
            echo '<tr>';
            echo "<td>{$order->kode_order}</td>";
            echo "<td>" . $order->created_at->format('d/m/Y') . "</td>";
            echo "<td>{$order->nama_pembeli}</td>";
            echo "<td>'{$order->no_hp}</td>"; // Tanda petik agar tidak jadi E+ (scientific)
            echo "<td>{$order->alamat_pembeli}</td>";
            echo "<td>Ayam Bangkok</td>"; // Default KATEGORI
            echo "<td>{$pesanan}</td>";
            echo "<td style='text-align:right;'>{$order->total_harga}</td>";
            echo '</tr>';
            
            return $order->total_harga;
        };

        // --- BAGIAN SELESAI ---
        $totalHargaDone = 0;
        if (count($doneOrders) > 0) {
            foreach ($doneOrders as $order) {
                $totalHargaDone += $renderRow($order);
            }
        } else {
            echo '<tr><td colspan="8">Tidak ada data selesai</td></tr>';
        }

        // Total Harga Done Row
        echo '<tr style="background-color:#f2f2f2; font-weight:bold;">';
        echo '<td colspan="7" style="text-align:right;">Total Pendapatan (Selesai)</td>';
        echo '<td style="text-align:right;">' . $totalHargaDone . '</td>';
        echo '</tr>';

        echo '</table>';
        exit;
    }
}
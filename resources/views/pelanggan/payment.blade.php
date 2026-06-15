@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-xl shadow">
    <h2 class="text-xl font-bold text-center mb-6">Selesaikan Pembayaran</h2>
    
    <div class="mb-6 p-4 bg-gray-50 rounded border">
        <p class="text-sm text-gray-500">Kode Order</p>
        <p class="font-bold text-lg mb-2">{{ $order->kode_order }}</p>
        <p class="text-sm text-gray-500">Total Tagihan</p>
        <p class="font-bold text-2xl text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
    </div>

    <!-- Tombol Midtrans -->
    <button id="pay-button" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg mb-3">
        Bayar Otomatis (Midtrans)
    </button>

    <!-- Tombol WA -->
    @php
        $noWaAdmin = "6281234567890";
        $pesanWa = "Halo Admin JagoFarm,%0A%0A";
        $pesanWa .= "Saya ingin konfirmasi pesanan dengan Kode Order : *" . $order->kode_order . "*%0A";
        $pesanWa .= "Total : *Rp " . number_format($order->total_harga, 0, ',', '.') . "*%0A%0A";
        $pesanWa .= "Mohon info rekeningnya.";
    @endphp
    <a href="[https://wa.me/](https://wa.me/){{ $noWaAdmin }}?text={{ $pesanWa }}" target="_blank" class="block text-center w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg">
        Konfirmasi via WhatsApp
    </a>
</div>

<!-- Script Midtrans -->
<script src="[https://app.sandbox.midtrans.com/snap/snap.js](https://app.sandbox.midtrans.com/snap/snap.js)" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
  document.getElementById('pay-button').onclick = function(){
    snap.pay('{{ $order->snap_token }}', {
      onSuccess: function(result){
        alert("Pembayaran Berhasil!"); window.location.href = '/';
      },
      onPending: function(result){
        alert("Menunggu Pembayaran!"); window.location.href = '/';
      },
      onError: function(result){
        alert("Pembayaran Gagal!");
      }
    });
  };
</script>
@endsection

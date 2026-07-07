@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-xl shadow">
    <h2 class="text-xl font-bold text-center mb-6">Selesaikan Pembayaran</h2>
    
    <div class="mb-6 p-4 bg-gray-50 rounded border">
        <p class="text-sm text-gray-500">Kode Order</p>
        <p class="font-bold text-lg mb-2">{{ $order->kode_order }}</p>
        <p class="text-sm text-gray-500">Metode Pengiriman / Pengambilan</p>
        <p class="font-bold text-lg mb-2">{{ $order->metode_pengiriman }}</p>

        @if($order->ekspedisi)
            <p class="text-sm text-gray-500">Biaya Ongkir ({{ $order->ekspedisi->nama_ekspedisi }})</p>
            <p class="font-bold text-lg mb-2">Rp {{ number_format($order->ekspedisi->ongkir_dasar, 0, ',', '.') }}</p>
        @endif

        <p class="text-sm text-gray-500 mt-2">Total Tagihan (Termasuk Ongkir)</p>
        <p class="font-bold text-2xl text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
    </div>

    @if(strtolower($order->metode_pengiriman) === 'cod')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg mb-6 text-center">
            <p class="font-bold text-lg mb-1"><i class="fa-solid fa-location-dot"></i> Pesanan COD Diterima!</p>
            <p class="text-sm">Silakan datang langsung ke lokasi peternakan JagoFarm untuk melakukan pembayaran tunai dan pengambilan ayam Anda.</p>
        </div>
    @else
        <!-- Tombol Midtrans -->
        <button id="pay-button" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg mb-3">
            Bayar Otomatis (Midtrans)
        </button>
    @endif

    <!-- Tombol WA -->
    @php
        $noWaAdmin = "6281234567890";
        $pesanWa = "Halo Admin JagoFarm,%0A%0A";
        $pesanWa .= "Saya ingin konfirmasi pesanan dengan Kode Order : *" . $order->kode_order . "*%0A";
        $pesanWa .= "Total : *Rp " . number_format($order->total_harga, 0, ',', '.') . "*%0A%0A";
        $pesanWa .= "Mohon info rekeningnya.";
    @endphp
    <a href="https://wa.me/{{ $noWaAdmin }}?text={{ $pesanWa }}" target="_blank" class="block text-center w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg">
        Konfirmasi via WhatsApp
    </a>
</div>

@if(strtolower($order->metode_pengiriman) !== 'cod' && $order->snap_token)
<!-- Script Midtrans -->
@php
    $isProduction = env('MIDTRANS_IS_PRODUCTION', false) === true || env('MIDTRANS_IS_PRODUCTION') === 'true';
    $snapUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp
<script src="{{ $snapUrl }}" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
  document.getElementById('pay-button').onclick = function(){
    snap.pay('{{ $order->snap_token }}', {
      onSuccess: function(result){
        alert("Pembayaran Berhasil! Mohon tunggu sebentar untuk sinkronisasi otomatis..."); 
        window.location.href = '{{ route("checkout.success", $order->id) }}';
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
@endif
@endsection

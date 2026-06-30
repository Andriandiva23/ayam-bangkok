@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pesanan Masuk</h2>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.pesanan.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            <span class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi Pelanggan</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm">
                        <th class="p-4 border-b font-semibold">KODE ORDER</th>
                        <th class="p-4 border-b font-semibold">NAMA PELANGGAN</th>
                        <th class="p-4 border-b font-semibold">TOTAL HARGA</th>
                        <th class="p-4 border-b font-semibold">PENGIRIMAN</th>
                        <th class="p-4 border-b font-semibold">STATUS</th>
                        <th class="p-4 border-b font-semibold">AKSI</th> <th class="p-4 border-b font-semibold">TANGGAL</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-4 font-bold text-primary">{{ $order->kode_order }}</td>
                        <td class="p-4">{{ $order->user->name ?? $order->nama_penerima }}</td>
                        <td class="p-4 font-medium">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        
                        <td class="p-4">
                            <span class="px-2 py-1 bg-gray-100 rounded-md text-xs font-bold uppercase">
                                {{ $order->metode_pengiriman }}
                            </span>
                        </td>
                        
                        <td class="p-4">
                            @if($order->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                            @elseif($order->status == 'dibayar')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Dibayar</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>

                        <td class="p-4">
                            @if($order->status == 'dibayar')
                                @if($order->status_pengiriman == 'dikirim')
                                    <span class="text-green-600 font-bold text-xs">Dikirim (Resi: {{ $order->nomor_resi }})</span>
                                @else
                                    <form action="{{ route('admin.pesanan.kirim', $order->id) }}" method="POST" class="flex gap-1">
                                        @csrf
                                        <input type="text" name="nomor_resi" placeholder="No Resi..." class="border rounded px-2 py-1 text-xs w-24" required>
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded text-xs">Kirim</button>
                                    </form>
                                @endif
                            @else
                                <span class="text-gray-400 text-xs italic">Menunggu Bayar</span>
                            @endif
                        </td>
                        
                        <td class="p-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">Belum ada pesanan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pesanan Masuk</h2>
        <span class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Daftar Pesanan</h3>
        </div>

        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm">
                            <th class="p-4 border-b font-semibold">KODE ORDER</th>
                            <th class="p-4 border-b font-semibold">TANGGAL</th>
                            <th class="p-4 border-b font-semibold">PEMBELI</th>
                            <th class="p-4 border-b font-semibold">TOTAL HARGA</th>
                            <th class="p-4 border-b font-semibold">STATUS</th>
                            <th class="p-4 border-b font-semibold text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="p-4 font-bold text-primary">{{ $order->kode_order }}</td>
                            <td class="p-4">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="p-4">{{ $order->user->name }}</td>
                            <td class="p-4 font-bold text-orange-500">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td class="p-4">
                                @if($order->status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pending</span>
                                @elseif($order->status == 'selesai')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Selesai</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <a href="#" class="text-blue-500 hover:text-blue-700 text-sm font-medium"><i class="fas fa-eye mr-1"></i> Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-16 flex flex-col items-center justify-center text-center">
                <div class="text-gray-300 mb-4">
                    <i class="fa-solid fa-receipt text-6xl"></i>
                </div>
                <p class="text-gray-500 text-lg font-medium">Data pesanan pelanggan akan<br>muncul di sini setelah checkout.</p>
            </div>
        @endif
        
    </div>
</div>
@endsection
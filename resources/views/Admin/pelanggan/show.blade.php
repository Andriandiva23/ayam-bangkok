@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Pelanggan</h2>
        <a href="{{ route('admin.pelanggan.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Info Pelanggan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex items-center gap-6">
        @php 
            $latestName = $pelanggan->orders->isNotEmpty() ? $pelanggan->orders->first()->nama_pembeli : $pelanggan->name; 
        @endphp
        <div class="bg-blue-100 w-24 h-24 rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold shadow-inner">
            {{ strtoupper(substr($latestName, 0, 1)) }}
        </div>
        <div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $latestName }}</h3>
            <p class="text-gray-500 mt-1"><i class="fas fa-envelope mr-2"></i> {{ $pelanggan->email }} (Akun: {{ $pelanggan->name }})</p>
            @if($pelanggan->orders->isNotEmpty() && $pelanggan->orders->first()->no_hp)
                <p class="text-gray-500 mt-1"><i class="fas fa-phone mr-2"></i> {{ $pelanggan->orders->first()->no_hp }}</p>
            @endif
            <p class="text-gray-500 mt-1"><i class="fas fa-calendar-alt mr-2"></i> Bergabung sejak {{ $pelanggan->created_at->translatedFormat('d F Y') }}</p>
        </div>
        <div class="ml-auto text-right">
            <p class="text-sm text-gray-400 font-bold uppercase mb-1">Total Pesanan</p>
            <p class="text-3xl font-extrabold text-primary">{{ $pelanggan->orders->count() }}</p>
        </div>
    </div>

    <!-- Riwayat Pesanan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-history mr-2"></i> Riwayat Pesanan Pelanggan</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-500 text-sm">
                        <th class="p-4 border-b font-semibold">KODE ORDER</th>
                        <th class="p-4 border-b font-semibold">TANGGAL</th>
                        <th class="p-4 border-b font-semibold">NO HP / WA</th>
                        <th class="p-4 border-b font-semibold">TOTAL BELANJA</th>
                        <th class="p-4 border-b font-semibold">STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($pelanggan->orders as $order)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-4 font-bold text-gray-800">#{{ $order->kode_order }}</td>
                        <td class="p-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4">{{ $order->no_hp }}</td>
                        <td class="p-4 text-orange-500 font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td class="p-4">
                            @if($order->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                            @elseif($order->status == 'dibayar')
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">Sudah Dibayar</span>
                            @elseif($order->status == 'dikirim')
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-bold">Dikirim</span>
                            @elseif($order->status == 'selesai')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Selesai</span>
                            @elseif($order->status == 'batal')
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 py-8">Pelanggan ini belum pernah melakukan pesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

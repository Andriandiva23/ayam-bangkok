@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Pesanan Masuk</h2>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <form action="{{ $is_selesai ? route('admin.pesanan.selesai') : route('admin.pesanan.index') }}" method="GET" class="flex-1 md:w-64">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari kode/nama/no hp..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </form>

            <a href="{{ route('admin.pesanan.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition whitespace-nowrap">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            <span class="text-gray-500 hidden md:inline">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>
    <div class="mb-6 flex items-center border-b border-gray-200">
        <a href="{{ route('admin.pesanan.index') }}" class="px-6 py-3 font-semibold {{ !$is_selesai ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }} transition">
            Pesanan Aktif
        </a>
        <a href="{{ route('admin.pesanan.selesai') }}" class="px-6 py-3 font-semibold {{ $is_selesai ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700' }} transition">
            Pesanan Selesai
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($is_selesai)
            <div class="p-6 border-b border-gray-100 bg-green-50">
                <h3 class="text-lg font-bold text-green-800"><i class="fas fa-check-double mr-2"></i>Semua Pesanan Selesai</h3>
            </div>
            @include('admin.pesanan._table', ['orders' => $orders, 'pageName' => 'page'])
        @else
            <!-- Tabel Pesanan COD -->
            <div class="p-6 border-b border-gray-100 bg-yellow-50">
                <h3 class="text-lg font-bold text-yellow-800"><i class="fas fa-hand-holding-usd mr-2"></i>Daftar Pesanan COD (Bayar di Tempat)</h3>
            </div>
            @include('admin.pesanan._table', ['orders' => $ordersCOD, 'pageName' => 'page_cod'])

            <!-- Divider -->
            <div class="h-4 bg-gray-100"></div>

            <!-- Tabel Pesanan Travel / Midtrans -->
            <div class="p-6 border-b border-gray-100 bg-blue-50">
                <h3 class="text-lg font-bold text-blue-800"><i class="fas fa-truck mr-2"></i>Daftar Pesanan Travel (Midtrans)</h3>
            </div>
            @include('admin.pesanan._table', ['orders' => $ordersTravel, 'pageName' => 'page_travel'])
        @endif
    </div>
</div>
@endsection
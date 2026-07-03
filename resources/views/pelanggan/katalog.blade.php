@extends('layouts.pelanggan')

@section('content')
<div class="bg-primary text-white shadow-inner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-5 tracking-tight">Ayam Bangkok Berkualitas Premium</h1>
        <p class="text-lg md:text-xl text-red-100 max-w-3xl mx-auto font-medium">
            Temukan ayam laga dan indukan terbaik dari peternakan kami.<br>
            Terintegrasi langsung dengan WhatsApp & Midtrans.
        </p>
    </div>
</div>

<div class="bg-gray-50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-8 border-b-2 border-gray-200 pb-4">Katalog Ayam Laga</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-8 flex items-center gap-3 font-medium shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($ayams as $ayam)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300">
                    
                    <div class="h-56 bg-primary flex items-center justify-center text-white font-bold text-3xl relative">
                        @if($ayam->foto)
                            <img src="{{ asset('storage/' . $ayam->foto) }}" alt="{{ $ayam->nama_ayam }}" class="w-full h-full object-cover">
                        @else
                            {{ $ayam->nama_ayam }}
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $ayam->nama_ayam }}</h3>
                        
                        <div class="flex gap-2 mb-3">
                            @if($ayam->jenis_kelamin == 'Jantan')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Jantan ♂️</span>
                            @elseif($ayam->jenis_kelamin == 'Betina')
                                <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-bold">Betina ♀️</span>
                            @endif
                            @if($ayam->berat)
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">Berat: {{ $ayam->berat }}</span>
                            @endif
                            @if($ayam->ukuran)
                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">Uk: {{ $ayam->ukuran }}</span>
                            @endif
                        </div>

                        <p class="text-2xl font-extrabold text-primary mb-5">Rp {{ number_format($ayam->harga, 0, ',', '.') }}</p>
                        
                        <div class="flex items-center text-gray-500 mb-6 font-medium">
                            <i class="fa-solid fa-box-open mr-2"></i> Stok: {{ $ayam->stok }}
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            @if($ayam->stok > 0)
                                <button @click="add({ id: {{ $ayam->id }}, nama: '{{ $ayam->nama_ayam }}', harga: {{ $ayam->harga }}, stok: {{ $ayam->stok }}, foto: '{{ $ayam->foto ? asset('storage/' . $ayam->foto) : '' }}' })" 
                                        class="w-full bg-primary hover:bg-red-800 text-white font-bold py-3.5 px-4 rounded-xl transition flex items-center justify-center gap-2 shadow-md">
                                    <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                                </button>
                            @else
                                <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3.5 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-ban"></i> Stok Habis
                                </button>
                            @endif

                            @if($ayam->link_video)
                                <a href="{{ $ayam->link_video }}" target="_blank" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 px-4 rounded-xl transition flex items-center justify-center gap-2 shadow-md mt-2">
                                    <i class="fa-brands fa-youtube"></i> Tonton Video di YouTube
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-500 bg-white rounded-2xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-box-open text-6xl mb-4 text-gray-300"></i>
                    <p class="text-xl font-medium">Belum ada ayam yang ditambahkan di katalog saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
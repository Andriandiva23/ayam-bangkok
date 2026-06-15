@extends('layouts.app')

@section('content')
<!-- Navbar -->
<nav class="bg-white shadow-sm p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-primary"><i class="fas fa-drumstick-bite"></i> JagoFarm</h1>
    <div>
        @auth
            <span class="mr-4">Halo, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-red-500 text-sm">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-primary font-bold">Login</a>
        @endauth
    </div>
</nav>

<!-- Katalog -->
<div class="max-w-7xl mx-auto p-8">
    <h2 class="text-2xl font-bold mb-6">Katalog Ayam Laga</h2>
    
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($ayams as $ayam)
        <div class="bg-white rounded-xl shadow p-4">
            <h3 class="font-bold text-lg">{{ $ayam->nama_ayam }}</h3>
            <p class="text-primary font-bold text-xl mb-2">Rp {{ number_format($ayam->harga, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mb-4">Stok: {{ $ayam->stok }} ekor</p>
            
            @auth
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ayam_id" value="{{ $ayam->id }}">
                    <div class="flex items-center space-x-2 mb-2">
                        <label class="text-sm">Jml:</label>
                        <input type="number" name="qty" value="1" min="1" max="{{ $ayam->stok }}" class="border rounded w-16 px-2 py-1">
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-2 rounded font-medium hover:bg-red-800 {{ $ayam->stok == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $ayam->stok == 0 ? 'disabled' : '' }}>
                        Beli Sekarang
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-center w-full bg-gray-200 text-gray-600 py-2 rounded">Login untuk Beli</a>
            @endauth
        </div>
        @endforeach
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6 gap-4">
        <a href="{{ route('admin.ekspedisi.index') }}" class="text-gray-500 hover:text-blue-600 transition">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Edit Layanan Ekspedisi</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full max-w-2xl">
        <form action="{{ route('admin.ekspedisi.update', $ekspedisi->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ekspedisi</label>
                <input type="text" name="nama_ekspedisi" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none" value="{{ $ekspedisi->nama_ekspedisi }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">No HP / WhatsApp Kurir</label>
                <input type="text" name="no_hp" required placeholder="08xxxxxxxxxx" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none" value="{{ $ekspedisi->no_hp }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Ongkir Dasar (Rp)</label>
                <input type="number" name="ongkir_dasar" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none" value="{{ $ekspedisi->ongkir_dasar }}">
            </div>
            
            <div class="mb-6 flex items-center mt-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
                <input type="checkbox" name="is_active" id="is_active" class="w-5 h-5 text-blue-600 rounded border-gray-300 mr-3 cursor-pointer" {{ $ekspedisi->is_active ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-bold text-gray-700 cursor-pointer">Aktifkan Layanan Ini (Bisa dipilih pelanggan saat checkout)</label>
            </div>
            
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.ekspedisi.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

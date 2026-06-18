@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Data Ayam</h2>
        <a href="{{ route('admin.ayam.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.ayam.update', $ayam->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Ayam</label>
                <input type="text" name="nama_ayam" value="{{ old('nama_ayam', $ayam->nama_ayam) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', $ayam->harga) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Stok (Ekor)</label>
                    <input type="number" name="stok" value="{{ old('stok', $ayam->stok) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('deskripsi', $ayam->deskripsi) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Foto Ayam Baru (Opsional)</label>
                @if($ayam->foto)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $ayam->foto) }}" alt="Foto saat ini" class="w-32 h-32 object-cover rounded shadow">
                        <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
                    </div>
                @endif
                <input type="file" name="foto" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" accept="image/*">
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto. Maksimal 2MB.</p>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-6 rounded shadow">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
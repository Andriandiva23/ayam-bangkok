@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Kategori Ayam</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-lg mb-4">Tambah Kategori Baru</h3>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required placeholder="Cth: Ayam Laga, Indukan..." class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Deskripsi Singkat (Opsional)</label>
                    <textarea name="deskripsi" rows="3" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-lg mb-4">Daftar Kategori</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b">
                            <th class="p-3">NAMA KATEGORI</th>
                            <th class="p-3">DESKRIPSI</th>
                            <th class="p-3">TOTAL AYAM</th>
                            <th class="p-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $kat)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-bold">{{ $kat->nama_kategori }}</td>
                            <td class="p-3 text-sm text-gray-500">{{ $kat->deskripsi ?? '-' }}</td>
                            <td class="p-3">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-bold">
                                    {{ $kat->ayams->count() }} Item
                                </span>
                            </td>
                            <td class="p-3">
                                <form action="{{ route('admin.kategori.destroy', $kat->id) }}" method="POST" class="delete-form" data-name="kategori {{ $kat->nama_kategori }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">Belum ada kategori yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
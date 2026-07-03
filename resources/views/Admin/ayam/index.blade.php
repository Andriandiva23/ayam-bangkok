@extends('layouts.admin') {{-- Sesuaikan dengan nama layout admin Anda --}}

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Ayam</h2>
        <span class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Daftar Ayam Bangkok</h3>
            <a href="{{ route('admin.ayam.create') }}" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded shadow">
                + Tambah Ayam
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm">
                        <th class="p-4 border-b font-semibold">FOTO</th>
                        <th class="p-4 border-b font-semibold">NAMA & INFO</th>
                        <th class="p-4 border-b font-semibold">HARGA</th>
                        <th class="p-4 border-b font-semibold">STOK</th>
                        <th class="p-4 border-b font-semibold text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($ayams as $ayam)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-4">
                            @if($ayam->foto)
                                <img src="{{ asset('storage/' . $ayam->foto) }}" alt="{{ $ayam->nama_ayam }}" class="w-12 h-12 rounded object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Image</div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-gray-800">{{ $ayam->nama_ayam }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($ayam->berat) Berat: <span class="font-semibold">{{ $ayam->berat }}</span> | @endif
                                @if($ayam->ukuran) Ukuran: <span class="font-semibold">{{ $ayam->ukuran }}</span> @endif
                            </div>
                            <div class="text-xs mt-1">
                                @if($ayam->jenis_kelamin == 'Jantan')
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-bold">Jantan ♂️</span>
                                @elseif($ayam->jenis_kelamin == 'Betina')
                                    <span class="bg-pink-100 text-pink-700 px-2 py-0.5 rounded font-bold">Betina ♀️</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-4 text-orange-500 font-bold">Rp {{ number_format($ayam->harga, 0, ',', '.') }}</td>
                        <td class="p-4">{{ $ayam->stok }} Ekor</td>
                        <td class="p-4 text-center">
                            <a href="{{ route('admin.ayam.edit', $ayam->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.ayam.destroy', $ayam->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin ingin menghapus ayam ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data ayam.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
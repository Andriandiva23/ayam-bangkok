@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Pelanggan</h2>
        <span class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Daftar Pelanggan Terdaftar</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm">
                        <th class="p-4 border-b font-semibold">NAMA PELANGGAN</th>
                        <th class="p-4 border-b font-semibold">EMAIL</th>
                        <th class="p-4 border-b font-semibold">TGL DAFTAR</th>
                        <th class="p-4 border-b font-semibold text-center">TOTAL PESANAN</th>
                        <th class="p-4 border-b font-semibold text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($pelanggans as $pelanggan)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-4">
                            <div class="font-bold text-gray-800">{{ $pelanggan->orders->isNotEmpty() ? $pelanggan->orders->first()->nama_pembeli : $pelanggan->name }}</div>
                            @if($pelanggan->orders->isNotEmpty() && $pelanggan->orders->first()->no_hp)
                                <div class="text-xs text-gray-500 mt-1"><i class="fas fa-phone mr-1"></i>{{ $pelanggan->orders->first()->no_hp }}</div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div>{{ $pelanggan->email }}</div>
                            <div class="text-xs text-gray-400 mt-1">Akun: {{ $pelanggan->name }}</div>
                        </td>
                        <td class="p-4">{{ $pelanggan->created_at->format('d M Y') }}</td>
                        <td class="p-4 text-center">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">{{ $pelanggan->orders_count }} Pesanan</span>
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('admin.pelanggan.show', $pelanggan->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">
                                <i class="fas fa-eye"></i> Detail & Riwayat
                            </a>
                            <form action="{{ route('admin.pelanggan.destroy', $pelanggan->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold" onclick="return confirm('Yakin ingin menghapus pelanggan ini? Semua data terkait pelanggan ini akan ikut terhapus.')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data pelanggan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

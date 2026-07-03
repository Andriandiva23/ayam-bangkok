@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Layanan Ekspedisi</h2>
        <button onclick="toggleModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Ekspedisi
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b">
                    <th class="p-3">NAMA EKSPEDISI</th>
                    <th class="p-3">NO HP / WA</th>
                    <th class="p-3">ONGKIR DASAR</th>
                    <th class="p-3">STATUS</th>
                    <th class="p-3">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ekspedisis as $eks)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold">{{ $eks->nama_ekspedisi }}</td>
                    <td class="p-3">{{ $eks->no_hp ?? '-' }}</td>
                    <td class="p-3">Rp {{ number_format($eks->ongkir_dasar, 0, ',', '.') }}</td>
                    <td class="p-3">
                        @if($eks->is_active)
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Aktif</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="p-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.ekspedisi.edit', $eks->id) }}" class="text-blue-500 hover:text-blue-700 text-sm font-bold">Edit</a>
                            <form action="{{ route('admin.ekspedisi.destroy', $eks->id) }}" method="POST" onsubmit="return confirm('Hapus ekspedisi ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data ekspedisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-lg text-gray-800">Tambah Ekspedisi Baru</h3>
            <button onclick="toggleModal('modalTambah')" class="text-gray-400 hover:text-red-500 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.ekspedisi.store') }}" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ekspedisi (Contoh: Kurir Pribadi)</label>
                <input type="text" name="nama_ekspedisi" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">No HP / WhatsApp Kurir</label>
                <input type="text" name="no_hp" required placeholder="08xxxxxxxxxx" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ongkir Dasar (Rp)</label>
                <input type="number" name="ongkir_dasar" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none" value="0">
            </div>
            <div class="mb-6 flex items-center mt-2">
                <input type="checkbox" name="is_active" id="is_active" checked class="w-4 h-4 text-blue-600 rounded border-gray-300 mr-2">
                <label for="is_active" class="text-sm font-medium text-gray-700">Aktifkan Layanan Ini</label>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="toggleModal('modalTambah')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle('hidden');
    }
</script>
@endsection
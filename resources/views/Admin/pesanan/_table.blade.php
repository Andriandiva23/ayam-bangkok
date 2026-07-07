<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-sm">
                <th class="p-4 border-b font-semibold">NO</th>
                <th class="p-4 border-b font-semibold">KODE ORDER</th>
                <th class="p-4 border-b font-semibold">NAMA PELANGGAN</th>
                <th class="p-4 border-b font-semibold">PESANAN</th>
                <th class="p-4 border-b font-semibold">TOTAL HARGA</th>
                <th class="p-4 border-b font-semibold">PENGIRIMAN</th>
                <th class="p-4 border-b font-semibold">STATUS</th>
                <th class="p-4 border-b font-semibold">AKSI</th> 
                <th class="p-4 border-b font-semibold">TANGGAL</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-4">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                <td class="p-4 font-bold text-primary">{{ $order->kode_order }}</td>
                <td class="p-4">
                    <div class="font-bold">{{ $order->nama_pembeli }}</div>
                    <div class="text-xs text-gray-500 mt-1"><i class="fas fa-phone mr-1"></i>{{ $order->no_hp }}</div>
                    <div class="text-xs text-gray-500 mt-1 line-clamp-2" title="{{ $order->alamat_pembeli }}">{{ $order->alamat_pembeli }}</div>
                </td>
                <td class="p-4">
                    @foreach($order->orderDetails as $detail)
                        <div class="flex items-center gap-2 mb-2">
                            @if($detail->ayam && $detail->ayam->foto)
                                <img src="{{ asset('storage/' . $detail->ayam->foto) }}" alt="Ayam" class="w-12 h-12 rounded object-cover shadow-sm">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400"><i class="fas fa-image"></i></div>
                            @endif
                            <div class="text-sm">
                                <div class="font-bold text-gray-700">{{ $detail->ayam ? $detail->ayam->nama_ayam : 'Ayam Dihapus' }}</div>
                                <div class="text-xs text-gray-500">{{ $detail->qty }} ekor</div>
                            </div>
                        </div>
                    @endforeach
                </td>
                <td class="p-4 font-medium">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                
                <td class="p-4">
                    <span class="px-2 py-1 bg-gray-100 rounded-md text-xs font-bold uppercase">
                        {{ $order->metode_pengiriman }}
                    </span>
                </td>
                
                <td class="p-4">
                    @if($order->status == 'pending')
                        @if(strtolower($order->metode_pengiriman) == 'cod')
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">Menunggu COD</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                        @endif
                    @elseif($order->status == 'dibayar')
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Dibayar</span>
                    @else
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($order->status) }}</span>
                    @endif
                </td>

                <td class="p-4">
                    @if($order->status == 'dibayar')
                        @if($order->status_pengiriman == 'dikirim')
                            <span class="text-green-600 font-bold text-xs">Dikirim @if($order->nomor_resi) (Wingbend/Pining: {{ $order->nomor_resi }}) @endif</span>
                        @else
                            <form action="{{ route('admin.pesanan.kirim', $order->id) }}" method="POST" class="flex flex-col gap-2 min-w-[200px]">
                                @csrf
                                <select name="ekspedisi_id" class="border border-gray-300 rounded px-2 py-1 text-xs w-full bg-white text-gray-700" required>
                                    <option value="">-- Pilih Ekspedisi --</option>
                                    @foreach($ekspedisis as $eks)
                                        <option value="{{ $eks->id }}" {{ $order->ekspedisi_id == $eks->id ? 'selected' : '' }}>
                                            {{ $eks->nama_ekspedisi }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="flex gap-1">
                                    <input type="text" name="nomor_resi" placeholder="No Wingbend/Pining (Opsional)" class="border border-gray-300 rounded px-2 py-1 text-xs flex-1 w-full">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-bold shadow-sm transition">Kirim</button>
                                </div>
                            </form>
                        @endif
                    @else
                        @if(strtolower($order->metode_pengiriman) == 'cod')
                            <form action="{{ route('admin.pesanan.bayar_cod', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs w-full mb-1">Terima Uang (COD)</button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs italic mb-1 block">Menunggu Bayar</span>
                            <a href="{{ route('admin.pesanan.sync_midtrans', $order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-[10px] font-bold w-full block text-center shadow-sm transition" title="Sinkronkan status dari Midtrans (untuk localhost)">
                                <i class="fas fa-sync-alt mr-1"></i> Sinkron Midtrans
                            </a>
                        @endif
                    @endif
                    
                    @if(Auth::user()->role === 'admin')
                        <form action="{{ route('admin.pesanan.destroy', $order->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini? Semua data terkait (termasuk mutasi) akan terhapus permanen.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-[10px] font-bold w-full block text-center shadow-sm transition">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                        </form>
                    @endif
                </td>
                
                <td class="p-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="p-4 text-center text-gray-500">Belum ada pesanan di kategori ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-4 border-t border-gray-100">
        {{ $orders->appends(request()->except($pageName))->links() }}
    </div>
</div>

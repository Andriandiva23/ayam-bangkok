@extends('layouts.admin')

@section('content')
<header class="bg-white shadow-sm border-b border-gray-200 px-10 py-6 flex justify-between items-center sticky top-0 z-10">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard<br><span class="text-gray-500 text-lg font-medium">Overview</span></h1>
    <div class="text-gray-500 font-medium">
        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</header>

<div class="p-10">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
            <div class="bg-blue-50 w-16 h-16 rounded-full flex items-center justify-center text-blue-500 shrink-0">
                <i class="fa-solid fa-arrow-trend-up text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Pendapatan Lunas</p>
                <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
            <div class="bg-green-50 w-16 h-16 rounded-full flex items-center justify-center text-green-500 shrink-0">
                <i class="fa-solid fa-basket-shopping text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Pesanan Sukses Diterima</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $pesananSelesai ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
            <div class="bg-orange-50 w-16 h-16 rounded-full flex items-center justify-center text-orange-500 shrink-0">
                <i class="fa-solid fa-box-open text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Stok Ayam Tersedia</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalStokAyam ?? 0 }}</h3>
            </div>
        </div>

    </div>
</div>

<div class="px-10 pb-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Daftar Top 5 Ayam -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[380px]">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Top 5 Jenis Ayam Terlaris</h3>
            <div class="overflow-y-auto flex-1 pr-2 space-y-3">
                @forelse($topAyams as $index => $item)
                    @php $ayam = $item->ayam; @endphp
                    <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-xl border border-gray-100 hover:bg-gray-100 transition">
                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shrink-0">
                            {{ $index + 1 }}
                        </div>
                        
                        @if($ayam && $ayam->foto)
                            <img src="{{ asset('storage/' . $ayam->foto) }}" alt="Foto" class="w-12 h-12 rounded-lg object-cover shadow-sm shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400 shrink-0">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800">{{ $ayam ? $ayam->nama_ayam : 'Ayam Dihapus' }}</h4>
                            <p class="text-xs text-gray-500">Kategori: {{ $ayam && $ayam->kategori ? $ayam->kategori->nama_kategori : '-' }}</p>
                        </div>
                        
                        <div class="text-right shrink-0">
                            <div class="text-sm font-bold text-green-600">{{ $item->total_terjual }} Ekor</div>
                            <div class="text-xs text-gray-500">Terjual</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-10">Belum ada data penjualan.</div>
                @endforelse
            </div>
        </div>

        <!-- Diagram Penjualan Bulanan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[380px]">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Grafik Penjualan Bulanan ({{ date('Y') }})</h3>
            <div class="relative flex-1 w-full mt-2">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Chart: Penjualan Bulanan
    const ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: {!! json_encode($monthlySalesData) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
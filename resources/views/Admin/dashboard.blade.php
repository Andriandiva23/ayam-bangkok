<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - JagoFarm Panel</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { 
                        primary: '#b91c1c',
                        sidebar: '#111827', 
                        sidebarHover: '#1f2937'
                    } 
                } 
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans flex h-screen overflow-hidden">

    <aside class="w-64 bg-sidebar text-white flex flex-col h-full shadow-lg">
        <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-800">
            <i class="fa-solid fa-drumstick-bite text-primary text-2xl"></i>
            <span class="text-xl font-bold tracking-wide">JagoFarm Panel</span>
        </div>

        <div class="px-6 py-5 border-b border-gray-800">
            <p class="text-xs text-gray-400 font-semibold mb-3 uppercase tracking-wider">Login Sebagai</p>
            <div class="flex items-center gap-3">
                <div class="bg-primary w-10 h-10 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-green-500 font-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3 bg-sidebarHover rounded-xl text-white transition-colors">
                <i class="fa-solid fa-house w-5 text-center"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('ayam.index') }}" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:bg-sidebarHover hover:text-white rounded-xl transition-colors">
                <i class="fa-solid fa-box w-5 text-center"></i>
                <span class="font-medium">Manajemen Ayam</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:bg-sidebarHover hover:text-white rounded-xl transition-colors">
                <i class="fa-solid fa-bag-shopping w-5 text-center"></i>
                <span class="font-medium">Pesanan Masuk</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-4 px-4 py-3 w-full text-gray-400 hover:text-primary transition-colors text-left">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 h-full overflow-y-auto">
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
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Penjualan</p>
                        <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
                    <div class="bg-green-50 w-16 h-16 rounded-full flex items-center justify-center text-green-500 shrink-0">
                        <i class="fa-solid fa-basket-shopping text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Pesanan Selesai</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $pesananSelesai ?? 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
                    <div class="bg-orange-50 w-16 h-16 rounded-full flex items-center justify-center text-orange-500 shrink-0">
                        <i class="fa-solid fa-box-open text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Stok Ayam</p>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $totalStokAyam ?? 0 }}</h3>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>
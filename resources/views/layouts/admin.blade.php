<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JagoFarm Panel</title>
    
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

    <aside class="w-64 bg-sidebar text-white flex flex-col h-full shadow-lg shrink-0">
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
                    <span class="text-xs text-gray-400 capitalize">{{ Auth::user()->role ?? 'admin' }}</span>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-sidebarHover text-white' : 'text-gray-400 hover:bg-sidebarHover hover:text-white' }} rounded-xl transition-colors">
                <i class="fa-solid fa-house w-5 text-center"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="{{ route('admin.ayam.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.ayam.*') ? 'bg-sidebarHover text-white' : 'text-gray-400 hover:bg-sidebarHover hover:text-white' }} rounded-xl transition-colors">
                <i class="fa-solid fa-box w-5 text-center"></i>
                <span class="font-medium">Manajemen Ayam</span>
            </a>
            
            <a href="{{ route('admin.pesanan.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.pesanan.*') ? 'bg-sidebarHover text-white' : 'text-gray-400 hover:bg-sidebarHover hover:text-white' }} rounded-xl transition-colors">
                <i class="fa-solid fa-bag-shopping w-5 text-center"></i>
                <span class="font-medium">Pesanan Masuk</span>
            </a>

            <a href="{{ route('admin.ekspedisi.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.ekspedisi.*') ? 'bg-sidebarHover text-white' : 'text-gray-400 hover:bg-sidebarHover hover:text-white' }} rounded-xl transition-colors">
                <i class="fas fa-truck w-5 text-center"></i>
                <span class="font-medium">Layanan Ekspedisi</span>
            </a>

            <a href="{{ route('admin.pelanggan.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.pelanggan.*') ? 'bg-sidebarHover text-white' : 'text-gray-400 hover:bg-sidebarHover hover:text-white' }} rounded-xl transition-colors">
                <i class="fas fa-users w-5 text-center"></i>
                <span class="font-medium">Manajemen Pelanggan</span>
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
        @yield('content')
    </main>

</body>
</html>
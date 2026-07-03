<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JagoFarm - Katalog</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#b91c1c' } } }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans" x-data="keranjang()">

    <nav class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-drumstick-bite text-primary text-3xl"></i>
                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight">JagoFarm</span>
                </div>

                <div class="flex items-center gap-6">
                    <button @click="cartOpen = true" class="relative text-gray-600 hover:text-primary transition p-2">
                        <i class="fa-solid fa-cart-shopping text-2xl"></i>
                        <span x-show="totalItems > 0" x-text="totalItems" x-cloak class="absolute top-0 right-0 bg-primary text-white text-[11px] font-bold px-1.5 py-0.5 rounded-full"></span>
                    </button>

                    <div class="flex items-center gap-3 border-l-2 pl-6 border-gray-100">
                        @auth
                            <div class="bg-gray-300 w-10 h-10 rounded-full flex items-center justify-center text-white shadow-sm">
                                <i class="fa-solid fa-user text-lg"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Logout</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary font-bold text-sm">Masuk</a>
                            <a href="{{ route('register') }}" class="bg-primary text-white hover:bg-red-800 font-bold px-4 py-2 rounded-xl text-sm transition">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-[#1f2937] text-gray-400 py-8 text-center mt-12">
        <p class="font-medium">© 2026 JagoFarm - Sistem Penjualan Ayam Bangkok.</p>
    </footer>

    <div x-show="cartOpen" x-cloak class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div x-show="cartOpen" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity"></div>
        
        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    
                    <div x-show="cartOpen" 
                         x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" 
                         x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" 
                         class="pointer-events-auto w-screen max-w-md">
                        
                        <div class="flex h-full flex-col bg-white shadow-2xl rounded-l-2xl">
                            <div class="flex items-center justify-between px-6 py-6 border-b border-gray-100">
                                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i> Keranjang Anda
                                </h2>
                                <button @click="cartOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                                    <i class="fa-solid fa-xmark text-2xl"></i>
                                </button>
                            </div>

                            <div class="flex-1 overflow-y-auto px-6 py-6">
                                <template x-if="items.length === 0">
                                    <div class="h-full flex flex-col items-center justify-center text-gray-400">
                                        <p class="text-lg">Keranjang belanja kosong.</p>
                                    </div>
                                </template>
                                
                                <ul class="-my-6 divide-y divide-gray-100">
                                    <template x-for="item in items" :key="item.id">
                                        <li class="flex py-6">
                                            <div class="h-24 w-24 flex-shrink-0 rounded-xl bg-primary flex items-center justify-center text-white text-xs font-bold text-center overflow-hidden shadow-sm">
                                                <template x-if="item.foto">
                                                    <img :src="item.foto" :alt="item.nama" class="h-full w-full object-cover">
                                                </template>
                                                <template x-if="!item.foto">
                                                    <span x-text="item.nama" class="p-2"></span>
                                                </template>
                                            </div>
                                            <div class="ml-4 flex flex-1 flex-col justify-center">
                                                <div class="flex justify-between text-base font-bold text-gray-800">
                                                    <h3 x-text="item.nama"></h3>
                                                    <button @click="removeItem(item.id)" class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                                                </div>
                                                <p class="mt-1 text-lg font-bold text-primary" x-text="'Rp ' + formatRupiah(item.harga)"></p>
                                                
                                                <div class="flex items-center mt-3 border border-gray-200 rounded-lg w-fit">
                                                    <button type="button" @click="decrement(item.id)" class="px-3 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-l-lg font-bold">-</button>
                                                    <span class="px-4 font-bold text-gray-800" x-text="item.qty"></span>
                                                    <button type="button" @click="increment(item.id)" class="px-3 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-r-lg font-bold">+</button>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <div class="border-t border-gray-100 px-6 py-6 bg-gray-50 rounded-bl-2xl">
                                <form action="{{ route('checkout.process') }}" method="POST">
                                    @csrf
                                    
                                    <input type="hidden" name="cart_items" :value="JSON.stringify(items)">
                                    <input type="hidden" name="total_harga" :value="totalPrice">

                                    <div class="mb-3">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="nama_pembeli" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp</label>
                                        <input type="text" name="no_hp" required placeholder="Contoh: 081234567890" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                                        <textarea name="alamat_pembeli" required placeholder="Sertakan Nama Jalan, RT/RW, Desa, Kecamatan, dan Kota/Kabupaten" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary text-sm"></textarea>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Metode Pengiriman</label>
                                        <select name="metode_pengiriman" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary text-sm bg-white">
                                            <option value="" disabled selected>-- Pilih Pengiriman --</option>
                                            <option value="travel">Kirim via Travel</option>
                                            <option value="cod">Bayar di Tempat (COD)</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-between text-base font-medium text-gray-600 mb-6 items-center border-t border-gray-200 pt-4">
                                        <p>Total Pembayaran:</p>
                                        <p class="text-3xl font-extrabold text-gray-800" x-text="'Rp ' + formatRupiah(totalPrice)"></p>
                                    </div>
                                    
                                    <button type="submit" :disabled="items.length === 0" class="w-full flex items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3.5 text-base font-bold text-white shadow hover:bg-red-800 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        <i class="fa-solid fa-clipboard-check"></i> Proses Pesanan Sekarang
                                    </button>
                                </form>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('keranjang', () => ({
                cartOpen: false,
                items: [],
                
                add(ayam) {
                    const existingItem = this.items.find(i => i.id === ayam.id);
                    if (existingItem) {
                        if(existingItem.qty < ayam.stok) existingItem.qty++;
                        else alert('Stok tidak mencukupi!');
                    } else {
                        this.items.push({ ...ayam, qty: 1 });
                    }
                    this.cartOpen = true; 
                },
                increment(id) {
                    const item = this.items.find(i => i.id === id);
                    if(item && item.qty < item.stok) item.qty++;
                },
                decrement(id) {
                    const item = this.items.find(i => i.id === id);
                    if (item && item.qty > 1) item.qty--;
                },
                removeItem(id) {
                    this.items = this.items.filter(i => i.id !== id);
                },
                get totalItems() {
                    return this.items.reduce((total, item) => total + item.qty, 0);
                },
                get totalPrice() {
                    return this.items.reduce((total, item) => total + (item.harga * item.qty), 0);
                },
                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka);
                }
            }))
        })
    </script>
</body>
</html>
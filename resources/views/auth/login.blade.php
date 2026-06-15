@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center mt-12 mb-12 px-4">
    
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md overflow-hidden">
        
        <div class="bg-primary p-8 text-center text-white">
            <i class="fa-solid fa-drumstick-bite text-5xl mb-4"></i>
            <h1 class="text-3xl font-bold mb-1">JagoFarm</h1>
            <p class="text-sm font-light opacity-90">Sistem Penjualan Ayam Bangkok</p>
        </div>
        
        <div class="p-8">
            <h2 class="text-2xl font-semibold text-center text-slate-700 mb-8">Masuk ke Akun</h2>
            
            <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" required
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-gray-50 hover:bg-white"
                            placeholder="admin@jagofarm.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-gray-50 hover:bg-white"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-primary text-white font-bold text-lg py-3.5 rounded-2xl hover:bg-red-800 transition duration-200 mt-4 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <span>Login</span>
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
                
            </form>
            
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center mt-12 mb-12 px-4">
    
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md overflow-hidden">
        
        <div class="bg-primary p-8 text-center text-white">
            <i class="fa-solid fa-user-plus text-5xl mb-4"></i>
            <h1 class="text-3xl font-bold mb-1">Daftar Akun</h1>
            <p class="text-sm font-light opacity-90">Buat akun untuk mulai memesan</p>
        </div>
        
        <div class="p-8">
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl relative mb-6 text-sm font-medium flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
            
            <form action="{{ route('register.process') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name" required
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-gray-50 hover:bg-white"
                            placeholder="Budi Santoso" value="{{ old('name') }}">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" required
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-gray-50 hover:bg-white"
                            placeholder="budi@email.com" value="{{ old('email') }}">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required minlength="6"
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-gray-50 hover:bg-white"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-check-circle text-gray-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6"
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-gray-50 hover:bg-white"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-primary text-white font-bold text-lg py-3.5 rounded-2xl hover:bg-red-800 transition duration-200 mt-4 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <span>Daftar Sekarang</span>
                </button>
                
                <p class="text-center text-sm text-gray-500 mt-6">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Masuk di sini</a>
                </p>
            </form>
            
        </div>
    </div>
</div>
@endsection

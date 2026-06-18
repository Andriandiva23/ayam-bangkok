<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan otentikasi/login masuk.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Cek role user yang baru saja login
        if ($request->user()->role === 'admin' || $request->user()->role === 'karyawan') {
            // Arahkan ke dashboard admin
            return redirect()->route('admin.dashboard'); 
        }

        // Arahkan ke katalog jika role-nya pelanggan
        // (Pastikan Anda sudah memberi nama ->name('katalog') pada route tampilan awal pelanggan)
        return redirect()->route('katalog'); 
    }

    /**
     * Mengakhiri sesi (Logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman Form Login
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Memproses pengecekan email dan password ke database
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi input agar tidak boleh kosong
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek apakah email dan password cocok dengan data di tabel users
        if (Auth::attempt($credentials)) {
            // Jika berhasil login, perbarui session untuk keamanan
            $request->session()->regenerate();

            // --- LOGIKA PENGATUR LALU LINTAS ROLE ---
            // Ambil role dari database dan paksa menjadi huruf kecil semua
            $role = strtolower(Auth::user()->role);

            // Cek apakah user yang login adalah admin atau karyawan
            if ($role === 'admin' || $role === 'karyawan') {
                return redirect()->route('admin.dashboard');
            } 
            
            // Jika bukan admin/karyawan (berarti pelanggan), arahkan ke Beranda/Katalog
            return redirect()->intended('/');
        }

        // 3. Jika gagal/salah password, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
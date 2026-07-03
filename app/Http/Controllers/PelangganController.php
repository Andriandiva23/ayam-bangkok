<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    // Menampilkan daftar pelanggan
    public function index()
    {
        // Ambil semua user dengan role 'pelanggan', sertakan data pesanan terakhir mereka
        $pelanggans = User::where('role', 'pelanggan')
            ->withCount('orders')
            ->with(['orders' => function($q) {
                $q->orderBy('created_at', 'desc')->limit(1); // Ambil pesanan terakhir
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    // Menampilkan detail pelanggan dan riwayat pesanannya
    public function show($id)
    {
        $pelanggan = User::with(['orders' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Keamanan ekstra: Pastikan yang dibuka benar-benar pelanggan
        if ($pelanggan->role !== 'pelanggan') {
            abort(404, 'Data pelanggan tidak ditemukan.');
        }

        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    // Menghapus pelanggan
    public function destroy($id)
    {
        $pelanggan = User::findOrFail($id);

        if ($pelanggan->role !== 'pelanggan') {
            abort(403, 'Anda tidak bisa menghapus akun ini.');
        }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Akun pelanggan berhasil dihapus.');
    }
}

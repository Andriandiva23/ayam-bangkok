<?php

namespace App\Http\Controllers;

use App\Models\Ayam;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AyamController extends Controller
{
    // --- Tampilan Pelanggan ---
    public function katalog() {
        // PERBAIKAN: Hanya mengambil ayam yang stoknya lebih dari 0
        $ayams = Ayam::where('stok', '>', 0)->get();
        return view('pelanggan.katalog', compact('ayams'));
    }

    // --- Tampilan Admin/Karyawan ---
    public function adminDashboard() {
        $ayams = Ayam::all();
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        $totalPenjualan = Order::where('status', 'selesai')->sum('total_harga'); 
        $pesananSelesai = Order::where('status', 'selesai')->count();
        $totalStokAyam = Ayam::sum('stok'); // Menggunakan sum('stok') agar akurat

        return view('admin.dashboard', compact(
            'ayams', 'orders', 'totalPenjualan', 'pesananSelesai', 'totalStokAyam'
        ));
    }

    // --- Manajemen Ayam ---

    // 1. Menampilkan Daftar Ayam
    public function index() {
        // Admin tetap bisa melihat semua ayam walaupun stoknya 0
        $ayams = Ayam::orderBy('created_at', 'desc')->get();
        return view('admin.ayam.index', compact('ayams')); 
    }

    // 2. Menampilkan Form Tambah Ayam
    public function create() {
        return view('admin.ayam.create');
    }

    // 3. Memproses Data Tambah Ayam
    public function store(Request $request) {
        // Validasi input
        $request->validate([
            'nama_ayam' => 'required|string|max:255',
            'harga'     => 'required|integer|min:0',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $data = $request->all();

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            // Simpan foto di folder storage/app/public/ayam_fotos
            $data['foto'] = $request->file('foto')->store('ayam_fotos', 'public');
        }

        Ayam::create($data);

        return redirect()->route('admin.ayam.index')->with('success', 'Data ayam berhasil ditambahkan!');
    }

    // 4. Menampilkan Form Edit Ayam
    public function edit(Ayam $ayam) {
        return view('admin.ayam.edit', compact('ayam'));
    }

    // 5. Memproses Perubahan Data Ayam
    public function update(Request $request, Ayam $ayam) {
        $request->validate([
            'nama_ayam' => 'required|string|max:255',
            'harga'     => 'required|integer|min:0',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Cek jika ada foto baru yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($ayam->foto) {
                Storage::disk('public')->delete($ayam->foto);
            }
            // Simpan foto baru
            $data['foto'] = $request->file('foto')->store('ayam_fotos', 'public');
        }

        $ayam->update($data);

        return redirect()->route('admin.ayam.index')->with('success', 'Data ayam berhasil diperbarui!');
    }

    // 6. Menghapus Data Ayam
    public function destroy(Ayam $ayam) {
        // Hapus foto fisik dari storage agar tidak memenuhi memori
        if ($ayam->foto) {
            Storage::disk('public')->delete($ayam->foto);
        }
        
        $ayam->delete();

        return redirect()->route('admin.ayam.index')->with('success', 'Data ayam berhasil dihapus!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Ekspedisi;
use Illuminate\Http\Request;

class EkspedisiController extends Controller
{
    public function index() {
        $ekspedisis = Ekspedisi::orderBy('created_at', 'desc')->get();
        return view('admin.ekspedisi.index', compact('ekspedisis'));
    }

   public function store(Request $request) {
        $request->validate([
            'nama_ekspedisi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20', // Validasi No HP
            'ongkir_dasar' => 'required|numeric|min:0',
        ]);

        Ekspedisi::create([
            'nama_ekspedisi' => $request->nama_ekspedisi,
            'no_hp' => $request->no_hp,          // Simpan No HP
            'ongkir_dasar' => $request->ongkir_dasar,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Layanan Ekspedisi berhasil ditambahkan!');
    }

    public function edit(Ekspedisi $ekspedisi) {
        return view('admin.ekspedisi.edit', compact('ekspedisi'));
    }

    public function update(Request $request, Ekspedisi $ekspedisi) {
        $request->validate([
            'nama_ekspedisi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'ongkir_dasar' => 'required|numeric|min:0',
        ]);

        $ekspedisi->update([
            'nama_ekspedisi' => $request->nama_ekspedisi,
            'no_hp' => $request->no_hp,
            'ongkir_dasar' => $request->ongkir_dasar,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.ekspedisi.index')->with('success', 'Layanan Ekspedisi berhasil diperbarui!');
    }

    public function destroy(Ekspedisi $ekspedisi) {
        $ekspedisi->delete();
        return back()->with('success', 'Layanan Ekspedisi berhasil dihapus!');
    }
}
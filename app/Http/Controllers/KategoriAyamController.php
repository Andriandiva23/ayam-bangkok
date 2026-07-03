<?php

namespace App\Http\Controllers;

use App\Models\KategoriAyam;
use Illuminate\Http\Request;

class KategoriAyamController extends Controller
{
    public function index()
    {
        $kategoris = KategoriAyam::orderBy('created_at', 'desc')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_ayams,nama_kategori',
            'deskripsi' => 'nullable|string'
        ]);

        KategoriAyam::create($request->all());

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, KategoriAyam $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_ayams,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string'
        ]);

        $kategori->update($request->all());

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(KategoriAyam $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}

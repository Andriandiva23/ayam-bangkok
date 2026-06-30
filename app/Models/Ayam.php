<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ayam extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ayam',
        'deskripsi',
        'harga',
        'stok',
        'foto',
        'kategori_id' // Tambahkan ini agar kategori bisa diinput/diupdate
    ];

    // Relasi: Ayam termasuk dalam satu Kategori (Belongs To)
    // Pastikan fungsi ini berada di DALAM kurung kurawal class Ayam
    public function kategori()
    {
        return $this->belongsTo(KategoriAyam::class, 'kategori_id');
    }
}
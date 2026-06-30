<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAyam extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang sesuai di database
    protected $table = 'kategori_ayams';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Relasi: Satu Kategori memiliki banyak Ayam (One to Many).
     * Menghubungkan tabel kategori_ayams ke tabel ayams.
     */
    public function ayams()
    {
        return $this->hasMany(Ayam::class, 'kategori_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ayam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_ayam',
        'deskripsi',
        'harga',
        'stok',
        'foto',
        'kategori_id',
        'jenis_kelamin',
        'link_video',
        'berat',
        'ukuran'
    ];

    // Relasi: Ayam termasuk dalam satu Kategori (Belongs To)
    // Pastikan fungsi ini berada di DALAM kurung kurawal class Ayam
    public function kategori()
    {
        return $this->belongsTo(KategoriAyam::class, 'kategori_id');
    }
}
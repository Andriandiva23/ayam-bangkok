<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ayam extends Model
{
    protected $fillable = [
        'nama_ayam',
        'deskripsi',
        'harga',
        'stok',
        'foto'
    ];
}

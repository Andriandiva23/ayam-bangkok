<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekspedisi extends Model
{
   protected $fillable = [
    'nama_ekspedisi', 
    'no_hp',         // Tambahkan baris ini
    'ongkir_dasar', 
    'is_active'
];

    // Relasi: Satu ekspedisi bisa digunakan di banyak pesanan (orders)
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
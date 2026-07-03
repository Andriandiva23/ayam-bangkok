<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'kode_order', 
        'nama_pembeli',      
        'no_hp',              
        'alamat_pembeli',     
        'metode_pengiriman', 
        'ekspedisi_id',       // Tambahan untuk relasi Ekspedisi
        'total_harga', 
        'status', 
        'snap_token',
        'status_pengiriman',  // Field untuk status pengiriman
        'nomor_resi'          // Field untuk nomor resi
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }

    // Relasi ke Ekspedisi
    public function ekspedisi() {
        return $this->belongsTo(Ekspedisi::class, 'ekspedisi_id');
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'kode_order', 
        'nama_penerima',      // Sesuaikan dengan Controller
        'no_hp',              // Sesuaikan dengan Controller
        'alamat_lengkap',     // Sesuaikan dengan Controller
        'metode_pengiriman', 
        'total_harga', 
        'status', 
        'snap_token',
        'status_pengiriman',
        'nomor_resi'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }
}
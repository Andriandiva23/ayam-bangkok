<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang sesuai di database
    protected $table = 'pengiriman';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'order_id',
        'metode_pengiriman',
        'nomor_resi',
        'nama_penerima',
        'no_hp_penerima',
        'alamat_penerima',
        'ongkos_kirim',
        'status_pengiriman'
    ];

    /**
     * Relasi: Data Pengiriman ini milik satu Order (Belongs To).
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
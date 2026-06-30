<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang sesuai di database
    protected $table = 'payments';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'order_id',
        'transaction_id',
        'jumlah_bayar',
        'metode_pembayaran',
        'status_pembayaran',
        'waktu_pembayaran'
    ];

    /**
     * Relasi: Data Pembayaran ini merujuk ke satu Order (Belongs To).
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
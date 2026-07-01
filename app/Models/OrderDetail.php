<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id', 
        'ayam_id', 
        'qty', 
        'harga', 
        'subtotal'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function ayam() {
        return $this->belongsTo(Ayam::class);
    }
}
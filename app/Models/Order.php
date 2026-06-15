<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'kode_order', 'total_harga', 'status', 'snap_token'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }
}

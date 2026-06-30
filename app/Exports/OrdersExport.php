<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection() {
        return Order::select('kode_order', 'nama_penerima', 'total_harga', 'status', 'metode_pengiriman', 'created_at')->get();
    }

    public function headings(): array {
        return ['Kode Order', 'Nama Pelanggan', 'Total Harga', 'Status', 'Metode Pengiriman', 'Tanggal'];
    }
}
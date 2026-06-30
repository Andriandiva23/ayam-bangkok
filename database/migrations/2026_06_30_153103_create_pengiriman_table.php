<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // TAMBAHKAN BARIS INI UNTUK MENGHAPUS TABEL YANG "SETENGAH JADI" SEBELUMNYA
        Schema::dropIfExists('pengiriman');

        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel orders
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete(); 
            
            $table->string('metode_pengiriman')->nullable(); 
                  
            $table->string('nomor_resi', 100)->nullable();
            $table->string('nama_penerima', 100);
            $table->string('no_hp_penerima', 20);
            $table->text('alamat_penerima');
            $table->integer('ongkos_kirim')->default(0);
            $table->enum('status_pengiriman', ['diproses', 'dikirim', 'selesai', 'gagal'])
                  ->default('diproses');
                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
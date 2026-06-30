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
        Schema::table('ayams', function (Blueprint $table) {
            // Menambahkan foreign key kategori_id yang mengarah ke tabel kategori_ayams
            // Dibuat nullable() agar data ayam lama yang belum punya kategori tidak error
            $table->foreignId('kategori_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('kategori_ayams')
                  ->nullOnDelete(); // Jika kategori dihapus, data ayam tidak ikut terhapus, hanya kategori_id-nya menjadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ayams', function (Blueprint $table) {
            // Menghapus relasi (foreign key) terlebih dahulu
            $table->dropForeign(['kategori_id']);
            // Baru menghapus kolomnya
            $table->dropColumn('kategori_id');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat tabel ekspedisis beserta no_hp secara langsung
        Schema::create('ekspedisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ekspedisi'); 
            $table->string('no_hp')->nullable(); 
            $table->decimal('ongkir_dasar', 10, 2)->default(0); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Menambahkan relasi ke tabel orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('ekspedisi_id')->nullable()->constrained('ekspedisis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['ekspedisi_id']);
            $table->dropColumn('ekspedisi_id');
        });
        Schema::dropIfExists('ekspedisis');
    }
};
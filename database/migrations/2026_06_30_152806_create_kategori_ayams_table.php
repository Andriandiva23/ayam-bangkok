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
        Schema::create('kategori_ayams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100)->unique(); // Contoh: Bangkok F1, Birma, Pakoy, Pakhoy-Birma
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_ayams');
    }
};
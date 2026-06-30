<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Di dalam file database/migrations/xxxx_xx_xx_xxxxxx_add_pengiriman_to_orders_table.php

public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        // Tuliskan kode Anda di sini
        $table->string('status_pengiriman')->default('menunggu konfirmasi'); // menunggu, dikirim, sampai
        $table->string('nomor_resi')->nullable(); //
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};

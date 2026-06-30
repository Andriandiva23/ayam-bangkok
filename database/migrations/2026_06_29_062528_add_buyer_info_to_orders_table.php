<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('nama_pembeli')->after('user_id');
        $table->text('alamat_pembeli')->after('nama_pembeli');
        $table->enum('metode_pengiriman', ['travel', 'cod'])->after('alamat_pembeli');
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

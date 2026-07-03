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
            $table->enum('jenis_kelamin', ['Jantan', 'Betina'])->nullable()->after('kategori_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ayams', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });
    }
};

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
            $table->string('link_video')->nullable();
            $table->string('berat')->nullable();
            $table->string('ukuran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ayams', function (Blueprint $table) {
            $table->dropColumn(['link_video', 'berat', 'ukuran']);
        });
    }
};

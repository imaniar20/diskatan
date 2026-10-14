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
        Schema::create('tanaman_pangans', function (Blueprint $table) {
            $table->id();
            $table->string('komoditas'); // Padi, Jagung, Kedelai, Kacang Tanah, Kacang Hijau, Ubi Kayu, Ubi Jalar
            $table->string('tahun', 10)->index(); // 2013, 2014, ... 2025
            $table->double('luas_tanam')->default(0); // Luas tanam (Ha)
            $table->double('luas_panen')->default(0); // Luas panen (Ha)
            $table->double('produktivitas')->default(0); // Produktivitas (Kw/Ha)
            $table->double('produksi')->default(0); // Total produksi (Ton)
            $table->string('satuan_luas', 30)->default('Ha');
            $table->string('satuan_produksi', 30)->default('Ton');
            $table->timestamps();

            $table->unique(['komoditas', 'tahun'], 'tanaman_pangan_komoditas_tahun_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanaman_pangans');
    }
};

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
        Schema::create('sayurans', function (Blueprint $table) {
            $table->id();
            $table->string('kategori')->default('Sayuran'); // Sayuran, Buah-buahan, Biofarmaka
            $table->string('komoditas'); // Bawang Merah, Bawang Daun, dll.
            $table->string('tahun', 10)->index(); // 2013, 2014, ... 2025
            $table->double('luas_tanam')->default(0); // Luas tanam (Ha, Pohon, M2)
            $table->double('luas_panen')->default(0); // Luas panen
            $table->double('produktivitas')->default(0); // Produktivitas
            $table->double('produksi')->default(0); // Total produksi
            $table->string('satuan_luas', 30)->default('Ha'); // Ha, Pohon, M²
            $table->string('satuan_produksi', 30)->default('Kuintal'); // Kuintal, Kg, Ton
            $table->timestamps();

            $table->unique(['komoditas', 'tahun'], 'sayuran_komoditas_tahun_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sayurans');
    }
};

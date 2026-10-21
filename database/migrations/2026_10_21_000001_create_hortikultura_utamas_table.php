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
        Schema::create('hortikultura_utamas', function (Blueprint $table) {
            $table->id();
            $table->string('komoditas');
            $table->string('kategori', 30)->default('tanam');
            $table->string('satuan', 20)->default('Ha');
            $table->string('tahun', 10)->default('2026');
            $table->string('musim_tanam', 50)->default('MT 2025/2026 dan MT 2026');
            $table->integer('no_urut')->nullable();
            $table->string('kecamatan');
            $table->decimal('luas_sawah', 12, 2)->default(0);
            
            // Target
            $table->decimal('target_okmar', 12, 2)->default(0);
            $table->decimal('target_asep', 12, 2)->default(0);
            $table->decimal('target_total', 12, 2)->default(0);
            
            // Realisasi MH (Okt - Mar)
            $table->decimal('realisasi_okt', 12, 2)->default(0);
            $table->decimal('realisasi_nop', 12, 2)->default(0);
            $table->decimal('realisasi_des', 12, 2)->default(0);
            $table->decimal('realisasi_jan', 12, 2)->default(0);
            $table->decimal('realisasi_feb', 12, 2)->default(0);
            $table->decimal('realisasi_mar', 12, 2)->default(0);
            $table->decimal('jumlah_mh', 12, 2)->default(0);
            
            // Realisasi MK (Apr - Sep)
            $table->decimal('realisasi_apr', 12, 2)->default(0);
            $table->decimal('realisasi_mei', 12, 2)->default(0);
            $table->decimal('realisasi_jun', 12, 2)->default(0);
            $table->decimal('realisasi_jul', 12, 2)->default(0);
            $table->decimal('realisasi_ags', 12, 2)->default(0);
            $table->decimal('realisasi_sep', 12, 2)->default(0);
            $table->decimal('jumlah_mk', 12, 2)->default(0);
            
            // Total & Persentase
            $table->decimal('total_realisasi', 12, 2)->default(0);
            $table->decimal('persentase_sub', 8, 2)->default(0);
            $table->decimal('persentase_capaian', 8, 2)->default(0);
            
            $table->timestamps();

            $table->index(['komoditas', 'kategori', 'tahun']);
            $table->index('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hortikultura_utamas');
    }
};

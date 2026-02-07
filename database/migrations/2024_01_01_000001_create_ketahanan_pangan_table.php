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
        Schema::create('ketahanan_pangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->enum('status_ketahanan', [
                'Sangat Tahan', 
                'Tahan', 
                'Agak Rawan', 
                'Rawan', 
                'Sangat Rawan'
            ]);
            $table->integer('jumlah_kk')->default(0);
            $table->year('tahun');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketahanan_pangan');
    }
};
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
        Schema::create('komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('sektor', 50)->index(); // 'tanaman_pangan', 'hortikultura', 'perkebunan'
            $table->string('nama', 100);
            $table->string('kategori_sayuran', 50)->nullable(); // e.g. Sayuran, Buah-buahan, Biofarmaka
            $table->string('satuan_luas', 20)->default('Ha');
            $table->string('satuan_produksi', 20)->default('Ton');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['sektor', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komoditas');
    }
};

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
        Schema::create('perkebunans', function (Blueprint $table) {
            $table->id();
            $table->string('komoditas'); // Kopi, Cengkeh, Kelapa, Lada, Tembakau, Aren, Kakao, Karet, Teh, Pala
            $table->string('tahun', 10);
            $table->decimal('luas_tanam', 12, 2)->default(0);
            $table->decimal('luas_panen', 12, 2)->default(0);
            $table->decimal('produktivitas', 12, 2)->default(0);
            $table->decimal('produksi', 12, 2)->default(0);
            $table->string('satuan_luas', 20)->default('Ha');
            $table->string('satuan_produksi', 20)->default('Ton');
            $table->timestamps();

            $table->index(['komoditas', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkebunans');
    }
};

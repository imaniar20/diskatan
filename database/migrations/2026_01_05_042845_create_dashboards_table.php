<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();

            // Statistik
            $table->integer('hektar_luas_tanam')->default(0);
            $table->integer('ton_produksi')->default(0);
            $table->integer('kelompok_tani')->default(0);
            $table->float('indeks_ketahanan_pangan')->default(0);

            // Informasi
            $table->text('ucapan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telphone', 20)->nullable();
            $table->string('jam_operasional')->nullable();

            // Sosial Media
            $table->text('youtube')->nullable();
            $table->text('instagram')->nullable();
            $table->text('tiktok')->nullable();
            $table->text('facebook')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboards');
    }
};

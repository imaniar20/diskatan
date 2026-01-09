<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->integer('hektar_luas_tanam')->nullable();
            $table->integer('ton_produksi')->nullable();
            $table->integer('kelompok_tani')->nullable();
            $table->float('indeks_ketahanan_pangan', 8, 2)->nullable();
            $table->string('nama_kadis')->nullable();
            $table->string('foto_kadis')->nullable();
            $table->text('ucapan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telphone')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->text('youtube')->nullable();
            $table->text('instagram')->nullable();
            $table->text('tiktok')->nullable();
            $table->text('facebook')->nullable();
            $table->timestamps();
        });

        DB::table('dashboards')->insert([
            'hektar_luas_tanam' => 0,
            'ton_produksi' => 0,
            'kelompok_tani' => 0,
            'indeks_ketahanan_pangan' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboards');
    }
};

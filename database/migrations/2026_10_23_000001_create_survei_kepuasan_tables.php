<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 150);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('pertanyaan_survei', function (Blueprint $table) {
            $table->id();
            $table->text('teks_pertanyaan');
            $table->string('icon', 50)->default('bi-star');
            $table->string('kunci_pertanyaan', 50);
            $table->integer('urutan')->default(1);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('responden', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->enum('usia', ['17-25', '26-35', '36-45', '46-55', '56+']);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('no_telepon', 25)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('survei_respons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responden_id')->constrained('responden')->onDelete('cascade');
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanan')->onDelete('restrict');
            $table->date('tanggal_survei');
            $table->text('komentar')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_rating', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survei_respons_id')->constrained('survei_respons')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan_survei')->onDelete('restrict');
            $table->tinyInteger('rating');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_rating');
        Schema::dropIfExists('survei_respons');
        Schema::dropIfExists('responden');
        Schema::dropIfExists('pertanyaan_survei');
        Schema::dropIfExists('jenis_layanan');
    }
};

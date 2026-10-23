<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisLayanan;
use App\Models\PertanyaanSurvei;

class SurveiKepuasanSeeder extends Seeder
{
    public function run(): void
    {
        $layananList = [
            ['nama_layanan' => 'Informasi Harga Pangan', 'deskripsi' => 'Layanan informasi perkembangan harga komoditas pangan terkini', 'status' => 'aktif'],
            ['nama_layanan' => 'Pengendalian Organisme Pengganggu Tanaman (OPT)', 'deskripsi' => 'Layanan penanganan serangan hama dan penyakit tanaman', 'status' => 'aktif'],
            ['nama_layanan' => 'Surat Rekomendasi Izin Pembelian Subsidi BBM', 'deskripsi' => 'Layanan rekomendasi pembelian BBM subsidi pertanian di SPBU', 'status' => 'aktif'],
            ['nama_layanan' => 'Distribusi Sarana dan Prasarana Pertanian', 'deskripsi' => 'Layanan bantuan sarana dan prasarana pertanian bagi kelompok tani', 'status' => 'aktif'],
            ['nama_layanan' => 'Surat Rekomendasi Izin Produk Pangan Segar Asal Tumbuhan (PSAT)', 'deskripsi' => 'Layanan perizinan dan sertifikasi beras serta sayuran konsumsi', 'status' => 'aktif'],
            ['nama_layanan' => 'Penyebarluasan Informasi Ketahanan Pangan dan Pertanian', 'deskripsi' => 'Layanan edukasi dan publikasi informasi ketahanan pangan', 'status' => 'aktif'],
            ['nama_layanan' => 'Lainnya', 'deskripsi' => 'Layanan umum kedinasan lainnya', 'status' => 'aktif'],
        ];

        foreach ($layananList as $item) {
            JenisLayanan::firstOrCreate(['nama_layanan' => $item['nama_layanan']], $item);
        }

        $pertanyaanList = [
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang kesesuaian persyaratan dan kemudahan prosedur pelayanan yang diberikan (tidak berbelit-belit)?',
                'icon' => 'bi-speedometer2',
                'kunci_pertanyaan' => 'prosedur',
                'urutan' => 1,
                'status' => 'aktif'
            ],
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang kecepatan waktu dalam memberikan pelayanan serta kesesuaian hasil dengan harapan Anda?',
                'icon' => 'bi-stopwatch',
                'kunci_pertanyaan' => 'kecepatan',
                'urutan' => 2,
                'status' => 'aktif'
            ],
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang kesesuaian produk pelayanan dengan standar yang ditetapkan serta kejelasan informasi biaya/tarif (layanan gratis)?',
                'icon' => 'bi-award',
                'kunci_pertanyaan' => 'produk',
                'urutan' => 3,
                'status' => 'aktif'
            ],
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang kompetensi, kesopanan, dan keramahan petugas dalam memberikan pelayanan?',
                'icon' => 'bi-person-check',
                'kunci_pertanyaan' => 'petugas',
                'urutan' => 4,
                'status' => 'aktif'
            ],
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang penanganan pengaduan, saran, dan masukan pengguna layanan yang ditindaklanjuti dengan baik?',
                'icon' => 'bi-chat-heart',
                'kunci_pertanyaan' => 'pengaduan',
                'urutan' => 5,
                'status' => 'aktif'
            ],
        ];

        foreach ($pertanyaanList as $item) {
            PertanyaanSurvei::firstOrCreate(['kunci_pertanyaan' => $item['kunci_pertanyaan']], $item);
        }
    }
}

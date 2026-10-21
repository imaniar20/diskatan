<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Komoditas;

class KomoditasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanamanPangan = [
            'Padi', 'Jagung', 'Kedelai', 'Kacang Tanah', 'Kacang Hijau', 'Ubi Kayu', 'Ubi Jalar'
        ];

        foreach ($tanamanPangan as $idx => $nama) {
            Komoditas::updateOrCreate(
                ['sektor' => 'tanaman_pangan', 'nama' => $nama],
                [
                    'satuan_luas' => 'Ha',
                    'satuan_produksi' => 'Ton',
                    'urutan' => $idx + 1,
                    'is_active' => true,
                ]
            );
        }

        $hortikultura = [
            ['nama' => 'Bawang Merah', 'kat' => 'Sayuran', 'luas' => 'Ha', 'prod' => 'Kuintal'],
            ['nama' => 'Bawang Daun', 'kat' => 'Sayuran', 'luas' => 'Ha', 'prod' => 'Kuintal'],
            ['nama' => 'Cabe Besar', 'kat' => 'Sayuran', 'luas' => 'Ha', 'prod' => 'Kuintal'],
            ['nama' => 'Cabe Rawit', 'kat' => 'Sayuran', 'luas' => 'Ha', 'prod' => 'Kuintal'],
            ['nama' => 'Tomat', 'kat' => 'Sayuran', 'luas' => 'Ha', 'prod' => 'Kuintal'],
            ['nama' => 'Wortel', 'kat' => 'Sayuran', 'luas' => 'Ha', 'prod' => 'Kuintal'],
            ['nama' => 'Kubis', 'kat' => 'Sayuran', 'luas' => 'Ha', 'prod' => 'Kuintal'],
            ['nama' => 'Petsai / Sawi', 'kat' => 'Sayuran', 'luas' => 'Ha', 'prod' => 'Kuintal'],
            ['nama' => 'Rambutan', 'kat' => 'Buah-buahan', 'luas' => 'Pohon', 'prod' => 'Kuintal'],
            ['nama' => 'Mangga', 'kat' => 'Buah-buahan', 'luas' => 'Pohon', 'prod' => 'Kuintal'],
            ['nama' => 'Durian', 'kat' => 'Buah-buahan', 'luas' => 'Pohon', 'prod' => 'Kuintal'],
            ['nama' => 'Jahe', 'kat' => 'Biofarmaka', 'luas' => 'M2', 'prod' => 'Kg'],
            ['nama' => 'Kapulaga', 'kat' => 'Biofarmaka', 'luas' => 'M2', 'prod' => 'Kg'],
        ];

        foreach ($hortikultura as $idx => $h) {
            Komoditas::updateOrCreate(
                ['sektor' => 'hortikultura', 'nama' => $h['nama']],
                [
                    'kategori_sayuran' => $h['kat'],
                    'satuan_luas' => $h['luas'],
                    'satuan_produksi' => $h['prod'],
                    'urutan' => $idx + 1,
                    'is_active' => true,
                ]
            );
        }

        $perkebunan = [
            'Kopi', 'Cengkeh', 'Kelapa', 'Lada', 'Tembakau',
            'Aren', 'Kakao', 'Karet', 'Teh', 'Pala'
        ];

        foreach ($perkebunan as $idx => $nama) {
            Komoditas::updateOrCreate(
                ['sektor' => 'perkebunan', 'nama' => $nama],
                [
                    'satuan_luas' => 'Ha',
                    'satuan_produksi' => 'Ton',
                    'urutan' => $idx + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}

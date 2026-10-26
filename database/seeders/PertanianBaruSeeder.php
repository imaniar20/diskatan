<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HortikulturaUtama;
use App\Models\PerkebunanUtama;
use App\Models\Perkebunan;
use App\Services\PadiPalawijaExcelService;

class PertanianBaruSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = PadiPalawijaExcelService::MASTER_KECAMATAN;
        $tahun = '2026';

        // 1. Seed Hortikultura Utama
        $hortiKomoditas = ['Bawang Merah', 'Bawang Daun', 'Cabe Besar', 'Cabe Rawit'];
        $hortiKategori = [
            'tanam' => ['satuan' => 'Ha', 'factor' => 1.0],
            'panen' => ['satuan' => 'Ha', 'factor' => 0.9],
            'produksi' => ['satuan' => 'Ton', 'factor' => 12.5],
            'produktivitas' => ['satuan' => 'Kw/Ha', 'factor' => 85.0],
            'puso' => ['satuan' => 'Ha', 'factor' => 0.05],
        ];

        foreach ($hortiKomoditas as $kmd) {
            foreach ($hortiKategori as $kat => $cfg) {
                foreach ($kecamatans as $kc) {
                    $base = (crc32($kmd . $kat . $kc['nama']) % 40) + 10;
                    $factor = $cfg['factor'];
                    
                    $targetOkmar = round($base * 0.5 * $factor, 2);
                    $targetAsep = round($base * 0.5 * $factor, 2);
                    $targetTotal = $targetOkmar + $targetAsep;

                    $rOkt = round(($base / 12) * 0.9 * $factor, 2);
                    $rNop = round(($base / 12) * 1.0 * $factor, 2);
                    $rDes = round(($base / 12) * 1.1 * $factor, 2);
                    $rJan = round(($base / 12) * 0.95 * $factor, 2);
                    $rFeb = round(($base / 12) * 0.85 * $factor, 2);
                    $rMar = round(($base / 12) * 1.05 * $factor, 2);
                    $sumMh = $rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar;

                    $rApr = round(($base / 12) * 1.0 * $factor, 2);
                    $rMei = round(($base / 12) * 0.9 * $factor, 2);
                    $rJun = round(($base / 12) * 1.1 * $factor, 2);
                    $rJul = round(($base / 12) * 0.95 * $factor, 2);
                    $rAgs = round(($base / 12) * 0.8 * $factor, 2);
                    $rSep = round(($base / 12) * 1.0 * $factor, 2);
                    $sumMk = $rApr + $rMei + $rJun + $rJul + $rAgs + $rSep;

                    $totReal = $sumMh + $sumMk;
                    $persenSub = $targetOkmar > 0 ? round(($sumMh / $targetOkmar) * 100, 2) : 0;
                    $persenTot = $targetTotal > 0 ? round(($totReal / $targetTotal) * 100, 2) : 0;

                    HortikulturaUtama::updateOrCreate(
                        [
                            'komoditas' => $kmd,
                            'kategori' => $kat,
                            'tahun' => $tahun,
                            'kecamatan' => $kc['nama'],
                        ],
                        [
                            'satuan' => $cfg['satuan'],
                            'musim_tanam' => 'MT 2025/2026 dan MT 2026',
                            'no_urut' => $kc['no'],
                            'luas_sawah' => round($base * 1.2, 2),
                            'target_okmar' => $targetOkmar,
                            'target_asep' => $targetAsep,
                            'target_total' => $targetTotal,
                            'realisasi_okt' => $rOkt,
                            'realisasi_nop' => $rNop,
                            'realisasi_des' => $rDes,
                            'realisasi_jan' => $rJan,
                            'realisasi_feb' => $rFeb,
                            'realisasi_mar' => $rMar,
                            'jumlah_mh' => $sumMh,
                            'realisasi_apr' => $rApr,
                            'realisasi_mei' => $rMei,
                            'realisasi_jun' => $rJun,
                            'realisasi_jul' => $rJul,
                            'realisasi_ags' => $rAgs,
                            'realisasi_sep' => $rSep,
                            'jumlah_mk' => $sumMk,
                            'total_realisasi' => $totReal,
                            'persentase_sub' => $persenSub,
                            'persentase_capaian' => $persenTot,
                        ]
                    );
                }
            }
        }

        // 2. Seed Perkebunan Utama
        $perkebunanKomoditas = ['Kopi', 'Cengkeh', 'Kelapa', 'Lada'];
        foreach ($perkebunanKomoditas as $kmd) {
            foreach ($hortiKategori as $kat => $cfg) {
                foreach ($kecamatans as $kc) {
                    $base = (crc32('pekebunan' . $kmd . $kat . $kc['nama']) % 35) + 8;
                    $factor = $cfg['factor'];
                    
                    $targetOkmar = round($base * 0.5 * $factor, 2);
                    $targetAsep = round($base * 0.5 * $factor, 2);
                    $targetTotal = $targetOkmar + $targetAsep;

                    $rOkt = round(($base / 12) * 0.9 * $factor, 2);
                    $rNop = round(($base / 12) * 1.0 * $factor, 2);
                    $rDes = round(($base / 12) * 1.1 * $factor, 2);
                    $rJan = round(($base / 12) * 0.95 * $factor, 2);
                    $rFeb = round(($base / 12) * 0.85 * $factor, 2);
                    $rMar = round(($base / 12) * 1.05 * $factor, 2);
                    $sumMh = $rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar;

                    $rApr = round(($base / 12) * 1.0 * $factor, 2);
                    $rMei = round(($base / 12) * 0.9 * $factor, 2);
                    $rJun = round(($base / 12) * 1.1 * $factor, 2);
                    $rJul = round(($base / 12) * 0.95 * $factor, 2);
                    $rAgs = round(($base / 12) * 0.8 * $factor, 2);
                    $rSep = round(($base / 12) * 1.0 * $factor, 2);
                    $sumMk = $rApr + $rMei + $rJun + $rJul + $rAgs + $rSep;

                    $totReal = $sumMh + $sumMk;
                    $persenSub = $targetOkmar > 0 ? round(($sumMh / $targetOkmar) * 100, 2) : 0;
                    $persenTot = $targetTotal > 0 ? round(($totReal / $targetTotal) * 100, 2) : 0;

                    PerkebunanUtama::updateOrCreate(
                        [
                            'komoditas' => $kmd,
                            'kategori' => $kat,
                            'tahun' => $tahun,
                            'kecamatan' => $kc['nama'],
                        ],
                        [
                            'satuan' => $cfg['satuan'],
                            'musim_tanam' => 'MT 2025/2026 dan MT 2026',
                            'no_urut' => $kc['no'],
                            'luas_sawah' => round($base * 1.5, 2),
                            'target_okmar' => $targetOkmar,
                            'target_asep' => $targetAsep,
                            'target_total' => $targetTotal,
                            'realisasi_okt' => $rOkt,
                            'realisasi_nop' => $rNop,
                            'realisasi_des' => $rDes,
                            'realisasi_jan' => $rJan,
                            'realisasi_feb' => $rFeb,
                            'realisasi_mar' => $rMar,
                            'jumlah_mh' => $sumMh,
                            'realisasi_apr' => $rApr,
                            'realisasi_mei' => $rMei,
                            'realisasi_jun' => $rJun,
                            'realisasi_jul' => $rJul,
                            'realisasi_ags' => $rAgs,
                            'realisasi_sep' => $rSep,
                            'jumlah_mk' => $sumMk,
                            'total_realisasi' => $totReal,
                            'persentase_sub' => $persenSub,
                            'persentase_capaian' => $persenTot,
                        ]
                    );
                }
            }
        }

        // 3. Seed Perkebunan Tahunan (Multi-Year 10 Tahun)
        $years = range(2015, 2024);
        foreach ($perkebunanKomoditas as $kmd) {
            foreach ($years as $idx => $yr) {
                $baseTanam = 1200 + ($idx * 45) + (crc32($kmd) % 300);
                $basePanen = round($baseTanam * 0.92, 2);
                $produktivitas = round(12.5 + ($idx * 0.3) + ((crc32($kmd) % 15) / 10), 2);
                $produksi = round(($basePanen * $produktivitas) / 10, 2);

                Perkebunan::updateOrCreate(
                    [
                        'komoditas' => $kmd,
                        'tahun' => (string)$yr,
                    ],
                    [
                        'luas_tanam' => $baseTanam,
                        'luas_panen' => $basePanen,
                        'produktivitas' => $produktivitas,
                        'produksi' => $produksi,
                        'satuan_luas' => 'Ha',
                        'satuan_produksi' => 'Ton',
                    ]
                );
            }
        }

        // 4. Seed Tanaman Pangan (13 Tahun: 2012 - 2024)
        if (\App\Models\TanamanPangan::count() === 0) {
            $tpKomoditas = ['Padi', 'Jagung', 'Kedelai', 'Kacang Tanah', 'Kacang Hijau', 'Ubi Kayu', 'Ubi Jalar'];
            $tpYears = range(2012, 2024);
            foreach ($tpKomoditas as $kmd) {
                foreach ($tpYears as $idx => $yr) {
                    $baseTanam = ($kmd === 'Padi') ? (55000 + ($idx * 350)) : (8000 + ($idx * 120) + (crc32($kmd) % 500));
                    $basePanen = round($baseTanam * 0.95, 2);
                    $produktivitas = ($kmd === 'Padi') ? round(62.0 + ($idx * 0.5), 2) : round(25.0 + ($idx * 0.3), 2);
                    $produksi = round(($basePanen * $produktivitas) / 10, 2);

                    \App\Models\TanamanPangan::create([
                        'komoditas' => $kmd,
                        'tahun' => (string)$yr,
                        'luas_tanam' => $baseTanam,
                        'luas_panen' => $basePanen,
                        'produktivitas' => $produktivitas,
                        'produksi' => $produksi,
                        'satuan_luas' => 'Ha',
                        'satuan_produksi' => 'Ton',
                    ]);
                }
            }
        }

        // 5. Seed Sayuran & Hortikultura (13 Tahun: 2012 - 2024)
        if (\App\Models\Sayuran::count() === 0) {
            $sayuranKomoditas = ['Bawang Merah', 'Bawang Daun', 'Cabe Besar', 'Cabe Rawit'];
            $sayuranYears = range(2012, 2024);
            foreach ($sayuranKomoditas as $kmd) {
                foreach ($sayuranYears as $idx => $yr) {
                    $baseTanam = 1500 + ($idx * 40) + (crc32($kmd) % 400);
                    $basePanen = round($baseTanam * 0.93, 2);
                    $produktivitas = round(80.0 + ($idx * 1.2) + ((crc32($kmd) % 30)), 2);
                    $produksi = round(($basePanen * $produktivitas), 2);

                    \App\Models\Sayuran::create([
                        'kategori' => in_array($kmd, ['Rambutan', 'Mangga', 'Durian']) ? 'Buah' : (in_array($kmd, ['Jahe', 'Kapulaga']) ? 'Biofarmaka' : 'Sayuran'),
                        'komoditas' => $kmd,
                        'tahun' => (string)$yr,
                        'luas_tanam' => $baseTanam,
                        'luas_panen' => $basePanen,
                        'produktivitas' => $produktivitas,
                        'produksi' => $produksi,
                        'satuan_luas' => 'Ha',
                        'satuan_produksi' => 'Kuintal',
                    ]);
                }
            }
        }
    }
}


<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Komoditas;
use App\Models\PadiPalawija;
use App\Models\TanamanPangan;
use App\Models\HortikulturaUtama;
use App\Models\Sayuran;
use App\Models\PerkebunanUtama;
use App\Models\Perkebunan;

class SyncPertanianTahunan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pertanian:sync-tahunan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bersihkan data dummy di Master Komoditas, rapikan urutan, dan sinkronkan Data Utama ke Data Tahunan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('1. Membersihkan Master Komoditas...');

        // Hapus komoditas testing jika ada
        $testKomoditas = Komoditas::whereRaw('LOWER(TRIM(nama)) = ?', ['test'])->first();
        if ($testKomoditas) {
            $testKomoditas->deleteRelatedAgriculturalData();
            $testKomoditas->delete();
            $this->warn('   - Komoditas dummy "test" berhasil dihapus.');
        } else {
            $this->line('   - Tidak ada komoditas dummy "test".');
        }

        // Rapikan urutan komoditas Tanaman Pangan
        $panganUrutan = [
            'Padi' => 1,
            'Jagung' => 2,
            'Kedelai' => 3,
            'Kacang Tanah' => 4,
            'Kacang Hijau' => 5,
            'Ubi Kayu' => 6,
            'Ubi Jalar' => 7,
        ];

        foreach ($panganUrutan as $nama => $no) {
            Komoditas::where('sektor', 'tanaman_pangan')
                ->where('nama', $nama)
                ->update(['urutan' => $no]);
        }
        $this->info('   - Urutan Tanaman Pangan diperbarui (Padi menjadi No. 1).');

        // 2. Sinkronkan Data Utama Padi Palawija -> Tanaman Pangan (Tahunan)
        $this->info('2. Sinkronisasi Data Utama Padi Palawija ke Data Tahunan...');
        $padiYears = PadiPalawija::select('tahun')->distinct()->pluck('tahun')->filter();

        $syncedPanganCount = 0;
        foreach ($padiYears as $th) {
            $komoditasList = PadiPalawija::where('tahun', $th)->distinct()->pluck('komoditas');
            foreach ($komoditasList as $kmd) {
                $tanam = (float) PadiPalawija::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'tanam')->sum('total_realisasi');
                $panen = (float) PadiPalawija::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'panen')->sum('total_realisasi');
                $produksi = (float) PadiPalawija::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'produksi')->sum('total_realisasi');
                
                // Produktivitas = (produksi * 10) / luas_panen dalam Kw/Ha, atau rata-rata dari kategori produktivitas
                $produktivitas = 0;
                if ($panen > 0 && $produksi > 0) {
                    $produktivitas = round(($produksi / $panen) * 10, 2);
                } else {
                    $avgProdv = (float) PadiPalawija::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'produktivitas')->avg('total_realisasi');
                    $produktivitas = round($avgProdv, 2);
                }

                TanamanPangan::updateOrCreate(
                    [
                        'komoditas' => $kmd,
                        'tahun' => (string) $th,
                    ],
                    [
                        'luas_tanam' => $tanam,
                        'luas_panen' => $panen,
                        'produktivitas' => $produktivitas,
                        'produksi' => $produksi,
                        'satuan_luas' => 'Ha',
                        'satuan_produksi' => 'Ton',
                    ]
                );
                $syncedPanganCount++;
            }
        }
        $this->info("   - {$syncedPanganCount} baris data Tanaman Pangan disinkronkan ke Data Tahunan.");

        // 3. Sinkronkan Data Utama Hortikultura -> Sayuran (Tahunan)
        $this->info('3. Sinkronisasi Data Utama Hortikultura ke Data Tahunan...');
        $hortiYears = HortikulturaUtama::select('tahun')->distinct()->pluck('tahun')->filter();

        $syncedHortiCount = 0;
        foreach ($hortiYears as $th) {
            $komoditasList = HortikulturaUtama::where('tahun', $th)->distinct()->pluck('komoditas');
            foreach ($komoditasList as $kmd) {
                $tanam = (float) HortikulturaUtama::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'tanam')->sum('total_realisasi');
                $panen = (float) HortikulturaUtama::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'panen')->sum('total_realisasi');
                $produksi = (float) HortikulturaUtama::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'produksi')->sum('total_realisasi');

                $produktivitas = 0;
                if ($panen > 0 && $produksi > 0) {
                    $produktivitas = round(($produksi / $panen) * 10, 2);
                } else {
                    $avgProdv = (float) HortikulturaUtama::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'produktivitas')->avg('total_realisasi');
                    $produktivitas = round($avgProdv, 2);
                }

                Sayuran::updateOrCreate(
                    [
                        'komoditas' => $kmd,
                        'tahun' => (string) $th,
                    ],
                    [
                        'kategori' => 'Sayuran',
                        'luas_tanam' => $tanam,
                        'luas_panen' => $panen,
                        'produktivitas' => $produktivitas,
                        'produksi' => $produksi,
                        'satuan_luas' => 'Ha',
                        'satuan_produksi' => 'Ton',
                    ]
                );
                $syncedHortiCount++;
            }
        }
        $this->info("   - {$syncedHortiCount} baris data Hortikultura disinkronkan ke Data Tahunan.");

        // 4. Sinkronkan Data Utama Perkebunan -> Perkebunan (Tahunan)
        $this->info('4. Sinkronisasi Data Utama Perkebunan ke Data Tahunan...');
        $perkebunanYears = PerkebunanUtama::select('tahun')->distinct()->pluck('tahun')->filter();

        $syncedPerkebunanCount = 0;
        foreach ($perkebunanYears as $th) {
            $komoditasList = PerkebunanUtama::where('tahun', $th)->distinct()->pluck('komoditas');
            foreach ($komoditasList as $kmd) {
                $tanam = (float) PerkebunanUtama::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'tanam')->sum('total_realisasi');
                $panen = (float) PerkebunanUtama::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'panen')->sum('total_realisasi');
                $produksi = (float) PerkebunanUtama::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'produksi')->sum('total_realisasi');

                $produktivitas = 0;
                if ($panen > 0 && $produksi > 0) {
                    $produktivitas = round(($produksi / $panen) * 10, 2);
                } else {
                    $avgProdv = (float) PerkebunanUtama::where('tahun', $th)->where('komoditas', $kmd)->where('kategori', 'produktivitas')->avg('total_realisasi');
                    $produktivitas = round($avgProdv, 2);
                }

                Perkebunan::updateOrCreate(
                    [
                        'komoditas' => $kmd,
                        'tahun' => (string) $th,
                    ],
                    [
                        'luas_tanam' => $tanam,
                        'luas_panen' => $panen,
                        'produktivitas' => $produktivitas,
                        'produksi' => $produksi,
                        'satuan_luas' => 'Ha',
                        'satuan_produksi' => 'Ton',
                    ]
                );
                $syncedPerkebunanCount++;
            }
        }
        $this->info("   - {$syncedPerkebunanCount} baris data Perkebunan disinkronkan ke Data Tahunan.");

        $this->info('Sinkronisasi selesai dengan sukses!');
        return Command::SUCCESS;
    }
}

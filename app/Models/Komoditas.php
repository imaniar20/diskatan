<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    use HasFactory;

    protected $table = 'komoditas';

    protected $fillable = [
        'sektor',
        'nama',
        'kategori_sayuran',
        'satuan_luas',
        'satuan_produksi',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public static array $sektorOptions = [
        'tanaman_pangan' => 'Tanaman Pangan',
        'hortikultura' => 'Hortikultura',
        'perkebunan' => 'Perkebunan',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSektor($query, $sektor)
    {
        return $query->where('sektor', $sektor);
    }

    /**
     * Memastikan komoditas terdaftar di master komoditas.
     * Jika belum ada, otomatis dibuatkan baris baru.
     */
    public static function firstOrCreateBySektor(string $sektor, string $nama, array $extraAttributes = []): ?self
    {
        $namaClean = trim($nama);
        if (empty($namaClean)) {
            return null;
        }

        $existing = self::where('sektor', $sektor)
            ->where(function ($q) use ($namaClean) {
                $q->whereRaw('LOWER(TRIM(nama)) = ?', [strtolower($namaClean)]);
            })
            ->first();

        if ($existing) {
            return $existing;
        }

        $lastUrutan = (int) self::where('sektor', $sektor)->max('urutan') ?: 0;

        return self::create([
            'sektor' => $sektor,
            'nama' => $namaClean,
            'kategori_sayuran' => $extraAttributes['kategori_sayuran'] ?? null,
            'satuan_luas' => $extraAttributes['satuan_luas'] ?? 'Ha',
            'satuan_produksi' => $extraAttributes['satuan_produksi'] ?? 'Ton',
            'urutan' => $lastUrutan + 1,
            'is_active' => true,
        ]);
    }

    /**
     * Sinkronisasi sekumpulan nama komoditas ke master komoditas untuk sektor tertentu.
     */
    public static function syncFromImport(string $sektor, array|string $namaList, array $defaults = []): array
    {
        $list = is_array($namaList) ? $namaList : [$namaList];
        $created = [];

        foreach ($list as $nama) {
            if (empty(trim((string)$nama))) {
                continue;
            }
            $item = self::firstOrCreateBySektor($sektor, (string)$nama, $defaults);
            if ($item && $item->wasRecentlyCreated) {
                $created[] = $item->nama;
            }
        }

        return $created;
    }

    /**
     * Mengambil koleksi model komoditas aktif untuk sektor tertentu.
     */
    public static function getBySektor(string $sektor)
    {
        return self::active()
            ->forSektor($sektor)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Mengambil daftar nama komoditas untuk sektor tertentu.
     */
    public static function getListBySektor(string $sektor): array
    {
        return self::active()
            ->forSektor($sektor)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->pluck('nama')
            ->toArray();
    }

    /**
     * Menghapus seluruh data pertanian terkait komoditas ini
     * sesuai sektor (Data Utama & Data Tahunan).
     */
    public function deleteRelatedAgriculturalData(?string $tahun = null): int
    {
        return self::deleteAgriculturalDataBySektorAndNama($this->sektor, $this->nama, $tahun);
    }

    /**
     * Menghapus data pertanian berdasarkan sektor dan nama komoditas.
     */
    public static function deleteAgriculturalDataBySektorAndNama(string $sektor, string $nama, ?string $tahun = null): int
    {
        $namaLower = strtolower(trim($nama));
        if (empty($namaLower)) {
            return 0;
        }

        $deleted = 0;

        if ($sektor === 'tanaman_pangan') {
            $q1 = PadiPalawija::whereRaw('LOWER(TRIM(komoditas)) = ?', [$namaLower]);
            $q2 = TanamanPangan::whereRaw('LOWER(TRIM(komoditas)) = ?', [$namaLower]);
            if ($tahun && $tahun !== 'ALL') {
                $q1->where('tahun', $tahun);
                $q2->where('tahun', $tahun);
            }
            $deleted += $q1->delete();
            $deleted += $q2->delete();
        } elseif ($sektor === 'hortikultura') {
            $q1 = HortikulturaUtama::whereRaw('LOWER(TRIM(komoditas)) = ?', [$namaLower]);
            $q2 = Sayuran::whereRaw('LOWER(TRIM(komoditas)) = ?', [$namaLower]);
            if ($tahun && $tahun !== 'ALL') {
                $q1->where('tahun', $tahun);
                $q2->where('tahun', $tahun);
            }
            $deleted += $q1->delete();
            $deleted += $q2->delete();
        } elseif ($sektor === 'perkebunan') {
            $q1 = PerkebunanUtama::whereRaw('LOWER(TRIM(komoditas)) = ?', [$namaLower]);
            $q2 = Perkebunan::whereRaw('LOWER(TRIM(komoditas)) = ?', [$namaLower]);
            if ($tahun && $tahun !== 'ALL') {
                $q1->where('tahun', $tahun);
                $q2->where('tahun', $tahun);
            }
            $deleted += $q1->delete();
            $deleted += $q2->delete();
        }

        return $deleted;
    }

    /**
     * Memperbarui nama komoditas pada tabel data pertanian jika nama komoditas diubah.
     */
    public static function renameAgriculturalData(string $sektor, string $oldNama, string $newNama): void
    {
        $oldLower = strtolower(trim($oldNama));
        $newClean = trim($newNama);
        if (empty($oldLower) || empty($newClean) || $oldLower === strtolower($newClean)) {
            return;
        }

        if ($sektor === 'tanaman_pangan') {
            PadiPalawija::whereRaw('LOWER(TRIM(komoditas)) = ?', [$oldLower])->update(['komoditas' => $newClean]);
            TanamanPangan::whereRaw('LOWER(TRIM(komoditas)) = ?', [$oldLower])->update(['komoditas' => $newClean]);
        } elseif ($sektor === 'hortikultura') {
            HortikulturaUtama::whereRaw('LOWER(TRIM(komoditas)) = ?', [$oldLower])->update(['komoditas' => $newClean]);
            Sayuran::whereRaw('LOWER(TRIM(komoditas)) = ?', [$oldLower])->update(['komoditas' => $newClean]);
        } elseif ($sektor === 'perkebunan') {
            PerkebunanUtama::whereRaw('LOWER(TRIM(komoditas)) = ?', [$oldLower])->update(['komoditas' => $newClean]);
            Perkebunan::whereRaw('LOWER(TRIM(komoditas)) = ?', [$oldLower])->update(['komoditas' => $newClean]);
        }
    }
}

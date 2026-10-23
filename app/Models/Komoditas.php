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
     * Mengambil daftar nama komoditas untuk sektor tertentu
     * dengan fallback default jika tabel masih kosong.
     */
    public static function getListBySektor(string $sektor): array
    {
        $list = self::active()
            ->forSektor($sektor)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->pluck('nama')
            ->toArray();

        if (!empty($list)) {
            return $list;
        }

        // Fallback default jika tabel belum diisi
        $defaults = match ($sektor) {
            'tanaman_pangan' => [
                'Padi', 'Jagung', 'Kedelai', 'Kacang Tanah', 'Kacang Hijau', 'Ubi Kayu', 'Ubi Jalar'
            ],
            'hortikultura' => [
                'Bawang Merah', 'Bawang Daun', 'Cabe Besar', 'Cabe Rawit', 'Tomat',
                'Wortel', 'Kubis', 'Petsai / Sawi', 'Rambutan', 'Mangga', 'Durian', 'Jahe', 'Kapulaga'
            ],
            'perkebunan' => [
                'Kopi', 'Cengkeh', 'Kelapa', 'Lada', 'Tembakau',
                'Aren', 'Kakao', 'Karet', 'Teh', 'Pala'
            ],
            default => []
        };

        if (!empty($defaults)) {
            try {
                self::syncFromImport($sektor, $defaults);
            } catch (\Throwable $e) {
                // Jangan gagalkan eksekusi jika ada kendala koneksi
            }
        }

        return $defaults;
    }
}

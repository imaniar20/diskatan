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
        return match ($sektor) {
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
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetahananPangan extends Model
{
    use HasFactory;

    protected $table = 'ketahanan_pangan';

    protected $fillable = [
        'nama_desa',
        'status_ketahanan',
        'jumlah_kk',
        'tahun',
        'keterangan'
    ];

    protected $casts = [
        'jumlah_kk' => 'integer',
        'tahun' => 'integer'
    ];

    // Konstanta untuk status
    const STATUS_SANGAT_TAHAN = 'Sangat Tahan';
    const STATUS_TAHAN = 'Tahan';
    const STATUS_AGAK_RAWAN = 'Agak Rawan';
    const STATUS_RAWAN = 'Rawan';
    const STATUS_SANGAT_RAWAN = 'Sangat Rawan';

    // Helper method untuk mendapatkan semua status
    public static function getAllStatus()
    {
        return [
            self::STATUS_SANGAT_TAHAN,
            self::STATUS_TAHAN,
            self::STATUS_AGAK_RAWAN,
            self::STATUS_RAWAN,
            self::STATUS_SANGAT_RAWAN
        ];
    }

    // Helper method untuk mendapatkan warna berdasarkan status
    public function getStatusColor()
    {
        return match($this->status_ketahanan) {
            self::STATUS_SANGAT_TAHAN => '#10b981',
            self::STATUS_TAHAN => '#84cc16',
            self::STATUS_AGAK_RAWAN => '#fbbf24',
            self::STATUS_RAWAN => '#f97316',
            self::STATUS_SANGAT_RAWAN => '#ef4444',
            default => '#6b7280'
        };
    }
}
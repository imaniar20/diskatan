<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PadiPalawija extends Model
{
    use HasFactory;

    protected $table = 'padi_palawijas';

    protected $fillable = [
        'komoditas',
        'tahun',
        'musim_tanam',
        'no_urut',
        'kecamatan',
        'luas_sawah',
        'target_okmar',
        'target_asep',
        'target_total',
        'realisasi_okt',
        'realisasi_nop',
        'realisasi_des',
        'realisasi_jan',
        'realisasi_feb',
        'realisasi_mar',
        'jumlah_mh',
        'realisasi_apr',
        'realisasi_mei',
        'realisasi_jun',
        'realisasi_jul',
        'realisasi_ags',
        'realisasi_sep',
        'jumlah_mk',
        'total_realisasi',
        'persentase_capaian',
    ];

    protected $casts = [
        'luas_sawah' => 'float',
        'target_okmar' => 'float',
        'target_asep' => 'float',
        'target_total' => 'float',
        'realisasi_okt' => 'float',
        'realisasi_nop' => 'float',
        'realisasi_des' => 'float',
        'realisasi_jan' => 'float',
        'realisasi_feb' => 'float',
        'realisasi_mar' => 'float',
        'jumlah_mh' => 'float',
        'realisasi_apr' => 'float',
        'realisasi_mei' => 'float',
        'realisasi_jun' => 'float',
        'realisasi_jul' => 'float',
        'realisasi_ags' => 'float',
        'realisasi_sep' => 'float',
        'jumlah_mk' => 'float',
        'total_realisasi' => 'float',
        'persentase_capaian' => 'float',
    ];
}

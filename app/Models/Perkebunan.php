<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perkebunan extends Model
{
    use HasFactory;

    protected $table = 'perkebunans';

    protected $fillable = [
        'komoditas',
        'tahun',
        'luas_tanam',
        'luas_panen',
        'produktivitas',
        'produksi',
        'satuan_luas',
        'satuan_produksi',
    ];

    protected $casts = [
        'luas_tanam'    => 'float',
        'luas_panen'    => 'float',
        'produktivitas' => 'float',
        'produksi'      => 'float',
    ];
}

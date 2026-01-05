<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboards extends Model
{
    use HasFactory;

    protected $table = 'dashboards';

    protected $fillable = [
        'hektar_luas_tanam',
        'ton_produksi',
        'kelompok_tani',
        'indeks_ketahanan_pangan',
        'ucapan',
        'alamat',
        'telphone',
        'jam_operasional',
        'youtube',
        'instagram',
        'tiktok',
        'facebook',
    ];
}

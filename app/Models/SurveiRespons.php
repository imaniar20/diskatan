<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiRespons extends Model
{
    use HasFactory;

    protected $table = 'survei_respons';
    protected $fillable = ['responden_id', 'jenis_layanan_id', 'tanggal_survei', 'komentar'];

    public function responden()
    {
        return $this->belongsTo(Responden::class, 'responden_id');
    }

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }

    public function detailRating()
    {
        return $this->hasMany(DetailRating::class, 'survei_respons_id');
    }
}

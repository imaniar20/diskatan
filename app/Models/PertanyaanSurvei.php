<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanSurvei extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_survei';
    protected $fillable = ['teks_pertanyaan', 'icon', 'kunci_pertanyaan', 'urutan', 'status'];

    public function detailRating()
    {
        return $this->hasMany(DetailRating::class, 'pertanyaan_id');
    }
}

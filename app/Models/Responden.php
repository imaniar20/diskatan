<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responden extends Model
{
    use HasFactory;

    protected $table = 'responden';
    protected $fillable = ['nama_lengkap', 'usia', 'jenis_kelamin', 'no_telepon', 'pekerjaan'];

    public function surveiRespons()
    {
        return $this->hasMany(SurveiRespons::class, 'responden_id');
    }
}

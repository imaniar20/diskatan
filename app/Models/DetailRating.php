<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRating extends Model
{
    use HasFactory;

    protected $table = 'detail_rating';
    protected $fillable = ['survei_respons_id', 'pertanyaan_id', 'rating'];

    public function surveiRespons()
    {
        return $this->belongsTo(SurveiRespons::class, 'survei_respons_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanSurvei::class, 'pertanyaan_id');
    }
}

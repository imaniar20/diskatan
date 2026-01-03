<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programs extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'kategori_id',
        'name',
        'status',
        'tahun'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function agendas(): HasMany
    {
        return $this->hasMany(Agendas::class, 'program_id');
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'program_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';

    protected $fillable = [
        'slug',
        'nama',
        'description',
        'icon',
    ];

    public function programs(): HasMany
    {
        return $this->hasMany(Programs::class, 'kategori_id');
    }
}

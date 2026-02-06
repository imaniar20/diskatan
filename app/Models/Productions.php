<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productions extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'value',
        'unit',
        'year',
        'created_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agendas extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'location',
        'date',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function programs(): BelongsTo
    {
        return $this->belongsTo(Programs::class, 'program_id');
    }
}

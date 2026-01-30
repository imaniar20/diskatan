<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News_Contents extends Model
{
    use HasFactory;

    protected $table = 'news_contents';
    protected $fillable = [
        'news_id',
        'file',
        'urutan',
    ];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}

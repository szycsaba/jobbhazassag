<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'title',
        'image_url',
        'subtitle',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}

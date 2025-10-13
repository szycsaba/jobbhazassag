<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnsiteBlock extends Model
{
    protected $fillable = [
        'position',
        'type_id',
        'content',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ArticleType::class, 'type_id');
    }
}

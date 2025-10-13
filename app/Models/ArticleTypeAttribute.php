<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleTypeAttribute extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'article_type_id';
    protected $keyType = 'int';

    protected $fillable = ['background', 'text', 'default_background', 'default_text'];

    public function articleType(): BelongsTo
    {
        return $this->belongsTo(ArticleType::class);
    }
}

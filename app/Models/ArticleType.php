<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArticleType extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function articleBlocks(): HasMany
    {
        return $this->hasMany(ArticleBlock::class, 'type_id');
    }

    public function onsiteBlocks(): HasMany
    {
        return $this->hasMany(OnsiteBlock::class, 'type_id');
    }

    public function articleTypeAttributes(): HasOne
    {
        return $this->hasOne(ArticleTypeAttribute::class);
    }
}

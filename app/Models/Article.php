<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
    ];

    public function articleBlocks(): HasMany
    {
        return $this->hasMany(ArticleBlock::class)->orderBy('position');
    }

    public function reflections(): HasMany
    {
        return $this->hasMany(Reflection::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function header(): HasOne
    {
        return $this->hasOne(Header::class);
    }
}

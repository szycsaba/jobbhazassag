<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReflectionQuestion extends Model
{
    protected $fillable = [
        'reflection_id',
        'position',
        'description',
    ];

    public function reflection(): BelongsTo
    {
        return $this->belongsTo(Reflection::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(UserReflectionNotes::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReflectionNotes extends Model
{
    protected $fillable = [
        'google_user_id',
        'reflection_question_id',
        'note_text',
    ];

    public function googleUser(): BelongsTo
    {
        return $this->belongsTo(GoogleUser::class);
    }

    public function reflectionQuestion(): BelongsTo
    {
        return $this->belongsTo(ReflectionQuestion::class);
    }
}

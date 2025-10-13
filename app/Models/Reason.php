<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reason extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_user_id',
        'content',
    ];

    public function googleUser(): BelongsTo
    {
        return $this->belongsTo(GoogleUser::class);
    }
}
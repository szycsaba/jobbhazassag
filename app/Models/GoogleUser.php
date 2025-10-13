<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GoogleUser extends Authenticatable
{
    protected $fillable = [
        'google_id',
        'name',
        'email',
        'email_verified_at',
        'avatar_url',
        'remember_token',
    ];

    public function reflectionNotes(): HasMany
    {
        return $this->hasMany(UserReflectionNotes::class);
    }

    public function reason(): HasOne
    {
        return $this->hasOne(Reason::class);
    }
}

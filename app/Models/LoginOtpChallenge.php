<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginOtpChallenge extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'provider_session_id',
        'attempts',
        'expires_at',
        'last_sent_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'last_sent_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Subscriber extends Model
{
    protected $fillable = [
        'stripe_customer_id',
        'email',
        'name',
        'stripe_subscription_id',
        'stripe_payment_intent_id',
        'stripe_session_id',
        'status',
        'payment_status',
        'amount_total',
        'currency',
        'subscription_started_at',
        'subscription_ends_at',
        'last_payment_at',
        'next_payment_at',
        'metadata',
        'customer_details',
    ];

    protected $casts = [
        'subscription_started_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'next_payment_at' => 'datetime',
        'metadata' => 'array',
        'customer_details' => 'array',
    ];

    /**
     * Get the amount in currency format (e.g., 1000 cents = 10.00)
     */
    protected function amountFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->amount_total ? number_format($this->amount_total / 100, 2) : null,
        );
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->payment_status === 'paid';
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isPast();
    }
}

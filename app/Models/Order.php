<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'delivery_address_id',
        'order_number',
        'total_amount',
        'payment_method',
        'order_status',
        'payment_status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'paid_at',
        'refund_status',
        'razorpay_refund_id',
        'refund_amount',
        'refund_requested_at',
        'refunded_at',
        'refund_reason',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'refund_amount' => 'decimal:2',
        'refund_requested_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryAddress(): BelongsTo
    {
        return $this->belongsTo(
            DeliveryAddress::class,
            'delivery_address_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
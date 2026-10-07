<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'price',
        'image',
        'status',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

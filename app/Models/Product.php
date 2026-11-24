<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CartItem;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'price',
        'image',
        'status',
    ];

    // Include image_url whenever the model is converted to JSON
    protected $appends = ['image_url'];

    // Full public URL of the product image (null if there is none)
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? url('api/product-image/' . $this->image) : null;
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DeliveryAddress extends Model
{

    protected $fillable = [
        'user_id',
        'address',
        'town',
        'city',
        'pincode',
        'type',
        'is_default',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

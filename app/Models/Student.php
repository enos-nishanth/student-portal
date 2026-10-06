<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'dob',
        'gender',
        'address',
        'city',
        'pincode',
        'qualification',
        'college',
        'graduation_year',
        'skills',
        'profile_image',
        'resume',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


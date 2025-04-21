<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $casts = [
        'images' => 'array',
    ];

    protected $fillable = [
    'user_id',
    'location',
    'description',
    'length',
    'width',
    'height',
    'size',
    'price_per_day',
    'price_per_month',
    'images',
    'is_available',
    'is_deleted',
];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'to_room_id');
    }
}


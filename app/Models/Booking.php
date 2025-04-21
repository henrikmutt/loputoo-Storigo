<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'renter_id',
        'total_price',
        'status',
        'agreement_accepted',
        'renter_requested_stop',
        'owner_requested_stop',
        'renter_confirmed_delivery',
        'owner_confirmed_delivery',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }
}

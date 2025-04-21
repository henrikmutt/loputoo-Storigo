<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'to_room_id',
        'rating',
        'comment',
        'is_deleted',
    ];

    public function from_user()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }


    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'to_room_id');
    }
}

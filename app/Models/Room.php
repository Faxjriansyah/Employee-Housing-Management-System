<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_code',
        'capacity',
        'current_occupancy',
        'status'
    ];

    public function occupancies()
    {
        return $this->hasMany(RoomOccupancy::class);
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomOccupancy extends Model
{
    protected $fillable = [
    'room_id',
    'employee_id',
    'guest_name',
    'type',
    'status',
    ];


    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}


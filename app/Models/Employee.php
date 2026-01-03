<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_code',
        'name',
        'department_id',
        'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function roomOccupancy()
    {
        return $this->hasOne(RoomOccupancy::class);
    }
    public function occupancies()
{
    return $this->hasMany(RoomOccupancy::class);
}

}


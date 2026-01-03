<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomOccupancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoomOccupancyController extends Controller
{
    /**
     * LIST OCCUPANCY AKTIF (TERISI)
     */
    public function index()
    {
        return response()->json(
            RoomOccupancy::with(['room', 'employee'])
                ->whereNull('checked_out_at')
                ->orderByDesc('created_at')
                ->get()
        );
    }

    /**
     * ASSIGN ROOM
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'type' => 'required|in:employee,guest',
            'employee_id' => 'required_if:type,employee|exists:employees,id',
            'guest_name' => 'required_if:type,guest|string|max:100',
        ]);

        DB::transaction(function () use ($request) {

            $room = Room::lockForUpdate()->findOrFail($request->room_id);

            if ($room->current_occupancy >= $room->capacity) {
                throw ValidationException::withMessages([
                    'room' => 'Room is already full'
                ]);
            }

            if ($request->type === 'employee') {
                $exists = RoomOccupancy::where('employee_id', $request->employee_id)
                    ->whereNull('checked_out_at')
                    ->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'employee' => 'Employee already assigned to a room'
                    ]);
                }
            }

            RoomOccupancy::create([
                'room_id' => $room->id,
                'employee_id' => $request->type === 'employee'
                    ? $request->employee_id
                    : null,
                'guest_name' => $request->type === 'guest'
                    ? $request->guest_name
                    : null,
                'type' => $request->type,
            ]);

            $room->increment('current_occupancy');

            if ($room->current_occupancy >= $room->capacity) {
                $room->update(['status' => 'full']);
            }
        });

        return response()->json(['message' => 'Room assigned successfully']);
    }

    /**
     * CHECKOUT
     */
   public function checkout(Request $request, $id)
{
    return DB::transaction(function () use ($request, $id) {

        $occupancy = RoomOccupancy::lockForUpdate()->findOrFail($id);

        if ($occupancy->checked_out_at !== null) {
            return response()->json(['message' => 'Already checked out'], 200);
        }

        $occupancy->checked_out_at = now();
        $occupancy->checkout_reason = $request->checkout_reason; // 👈 BARU
        $occupancy->save();

        $activeCount = RoomOccupancy::where('room_id', $occupancy->room_id)
            ->whereNull('checked_out_at')
            ->count();

        $room = Room::lockForUpdate()->findOrFail($occupancy->room_id);
        $room->current_occupancy = $activeCount;
        $room->status = $activeCount >= $room->capacity ? 'full' : 'available';
        $room->save();

        return response()->json(['message' => 'Checkout successful']);
    });
}


public function stats()
{
    return DB::table('room_occupancies')
        ->join('rooms', 'rooms.id', '=', 'room_occupancies.room_id')
        ->select(
            'rooms.room_code',
            'room_occupancies.type',
            DB::raw('COUNT(room_occupancies.id) as total_usage')
        )
        ->groupBy('rooms.room_code', 'room_occupancies.type')
        ->orderBy('rooms.room_code')
        ->get();
}




    /**
     * HISTORY
     */
    public function history()
    {
        return response()->json(
            RoomOccupancy::with(['room', 'employee'])
                ->whereNotNull('checked_out_at')
                ->orderByDesc('checked_out_at')
                ->get()
        );
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::orderBy('room_code')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_code' => 'required|unique:rooms,room_code',
            'capacity' => 'required|in:1,2'
        ]);

        Room::create([
            'room_code' => $request->room_code,
            'capacity' => $request->capacity,
            'current_occupancy' => 0,
            'status' => 'available'
        ]);

        return response()->json(['message' => 'Room created successfully']);
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'capacity' => 'required|in:1,2'
        ]);

        $room->update([
            'capacity' => $request->capacity
        ]);

        return response()->json(['message' => 'Room updated successfully']);
    }

    public function show(Room $room)
    {
        return $room->load('occupancies.employee');
    }

    // Optional: Tambahkan method delete jika diperlukan
    public function destroy(Room $room)
    {
        // Cek apakah room sedang digunakan
        if ($room->current_occupancy > 0) {
            return response()->json([
                'message' => 'Cannot delete room with occupants'
            ], 400);
        }

        $room->delete();
        return response()->json(['message' => 'Room deleted successfully']);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // 20 kamar kapasitas 1
        for ($i = 1; $i <= 20; $i++) {
            DB::table('rooms')->insert([
                'room_code' => 'S1-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => 1,
                'current_occupancy' => 0,
                'status' => 'available',
            ]);
        }

        // 25 kamar kapasitas 2
        for ($i = 1; $i <= 25; $i++) {
            DB::table('rooms')->insert([
                'room_code' => 'D2-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => 2,
                'current_occupancy' => 0,
                'status' => 'available',
            ]);
        }
    }
}

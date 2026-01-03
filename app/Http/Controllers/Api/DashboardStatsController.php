<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardStatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'totalEmployee' => DB::table('employees')->count(),

            'activeEmployee' => DB::table('employees')
                ->where('is_active', true)
                ->count(),

            'occupiedRoom' => DB::table('rooms')
                ->where('status', 'full')
                ->count(),

            'weeklyGuest' => DB::table('room_occupancies')
                ->where('type', 'guest')
                ->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])
                ->count(),

            'employeeChart' => DB::table('employees')
                ->selectRaw('
                    CASE 
                        WHEN is_active = 1 THEN "Aktif"
                        ELSE "Nonaktif"
                    END as status,
                    COUNT(*) as total
                ')
                ->groupBy('is_active')
                ->get(),

            'roomChart' => DB::table('rooms')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->get(),
        ]);
    }
}

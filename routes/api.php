<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\RoomOccupancyController;
use App\Http\Controllers\Api\DashboardStatsController;

/*
|--------------------------------------------------------------------------
| MASTER DATA
|--------------------------------------------------------------------------
*/
Route::get('departments', [DepartmentController::class, 'index']);

/*
|--------------------------------------------------------------------------
| EMPLOYEES
|--------------------------------------------------------------------------
*/
Route::apiResource('employees', EmployeeController::class)->except(['create', 'edit']);
Route::patch('employees/{employee}/toggle', [EmployeeController::class, 'toggle']);

/*
|--------------------------------------------------------------------------
| ROOMS
|--------------------------------------------------------------------------
*/
Route::apiResource('rooms', RoomController::class)->except(['create', 'edit']);
Route::get('rooms-available', [RoomController::class, 'available']);

/*
|--------------------------------------------------------------------------
| ROOM OCCUPANCIES
|--------------------------------------------------------------------------
*/
// list occupancy aktif
Route::get('room-occupancies', [RoomOccupancyController::class, 'index']);

// assign room
Route::post('room-occupancies', [RoomOccupancyController::class, 'store']);

// checkout (inactive)
Route::post('room-occupancies/{id}/checkout', [RoomOccupancyController::class, 'checkout']);

// history (inactive)
Route::get('room-occupancies/history', [RoomOccupancyController::class, 'history']);

// hard delete (ADMIN ONLY – opsional)
Route::delete('room-occupancies/{id}', [RoomOccupancyController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| DASHBOARD / STATS
|--------------------------------------------------------------------------
*/
Route::get('stats/room-occupancy', [RoomOccupancyController::class, 'stats']);
Route::get('dashboard/stats', [DashboardStatsController::class, 'index']);

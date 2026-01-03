<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeePageController;
use App\Http\Controllers\RoomPageController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (PUBLIC)
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Default redirect setelah login
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Employees
    Route::get('/employees', [EmployeePageController::class, 'index'])
        ->name('employees.index');

    // Rooms
    Route::get('/rooms', [RoomPageController::class, 'index'])
        ->name('rooms.index');

    // Occupancies
    Route::get('/occupancies', function () {
        return view('occupancies.index');
    })->name('occupancies.index');

    Route::get('/occupancies/history', function () {
        return view('occupancies.history');
    })->name('occupancies.history');

    // Reports
    Route::get('/reports/occupancy', function () {
        return view('reports.occupancy-pivot');
    })->name('reports.occupancy');
});

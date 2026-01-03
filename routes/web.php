<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeePageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomPageController;
use Illuminate\Support\Facades\Auth;


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/employees', [EmployeePageController::class, 'index']);



Route::get('/rooms', [RoomPageController::class, 'index']);

Route::get('/occupancies', function () {
    return view('occupancies.index');
});

Route::get('/occupancies/history', function () {
    return view('occupancies.history');
});




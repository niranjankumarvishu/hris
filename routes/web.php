<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')
    ->name('home');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::resource('employees', EmployeeController::class)
    ->except(['show'])
    ->middleware(['auth']);

Route::resource('leaves', LeaveController::class)
    ->parameters([
        'leaves' => 'leave',
    ])
    ->middleware(['auth']);

Route::post(
    'leaves/{leave}/approve',
    [LeaveController::class, 'approve']
)
    ->middleware(['auth'])
    ->name('leaves.approve');

Route::post(
    'leaves/{leave}/reject',
    [LeaveController::class, 'reject']
)
    ->middleware(['auth'])
    ->name('leaves.reject');

Route::get(
    'leave-statistics',
    [LeaveController::class, 'statistics']
)
    ->middleware(['auth'])
    ->name('leaves.statistics');
Route::middleware(['auth'])->group(function () {
    Route::get(
        'attendance',
        [AttendanceController::class, 'index']
    )->name('attendance.index');

    Route::post(
        'attendance',
        [AttendanceController::class, 'store']
    )->name('attendance.store');

    Route::delete(
        'attendance/{attendance}',
        [AttendanceController::class, 'destroy']
    )->name('attendance.destroy');
});
require __DIR__.'/settings.php';
<?php

use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/attendance-summary',
    [AttendanceController::class, 'summary']
);
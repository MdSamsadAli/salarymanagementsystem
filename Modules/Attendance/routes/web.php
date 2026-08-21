<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\AttendanceController;

Route::middleware(['auth'])->prefix('attendance')->name('attendance.')->group(function () {
    // existing self-service routes...
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/history/{employee}', [AttendanceController::class, 'history'])->name('history');
    Route::post('/check-in/{employee}', [AttendanceController::class, 'CheckIn'])->name('admin-check-in');
    Route::post('/check-out/{employee}', [AttendanceController::class, 'CheckOut'])->name('admin-check-out');
});

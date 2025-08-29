<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;

// Health
Route::get('/health', fn () => response()->noContent())->name('health');

// Auth (guest)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected app
Route::middleware('auth.simple')->group(function () {
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');

    Route::redirect('/', '/vehicles');

    Route::resource('vehicles', VehicleController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::resource('drivers', DriverController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::post('/vehicles/{vehicle}/assign', [AssignmentController::class, 'assign'])->name('vehicles.assign');
    Route::post('/vehicles/{vehicle}/release', [AssignmentController::class, 'release'])->name('vehicles.release');
});

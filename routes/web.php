<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\AssignmentController;


// Health (simple infra check)
Route::get('/health', fn () => response()->noContent());

// Dashboard → vehicles
Route::redirect('/', '/vehicles');

// vehicles CRUD (you use .store/.update in your partials)
Route::resource('vehicles', VehicleController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

// drivers CRUD (backend only for now)
Route::resource('drivers', DriverController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

// assignment actions (backend-only endpoints)
Route::post('/vehicles/{vehicle}/assign', [AssignmentController::class, 'assign'])->name('vehicles.assign');
Route::post('/vehicles/{vehicle}/release', [AssignmentController::class, 'release'])->name('vehicles.release');

// (Optional) If you still have create/edit/destroy actions working and want them, keep them. 
// Otherwise, comment/delete the lines below until you need them.
// Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
// Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
// Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
// Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

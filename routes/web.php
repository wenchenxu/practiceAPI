<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Http\Controllers\VehicleController;

// Health (simple infra check)
Route::get('/health', fn () => response()->noContent());

// Dashboard → vehicles
Route::redirect('/', '/vehicles');

// Vehicles list (controller-based)
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');

// (Optional) If you still have create/edit/destroy actions working and want them, keep them. 
// Otherwise, comment/delete the lines below until you need them.
// Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
// Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
// Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
// Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

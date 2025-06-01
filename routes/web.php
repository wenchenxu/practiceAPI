<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarKeyController;
use App\Http\Controllers\VehicleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/car-key/token-status', [CarKeyController::class, 'tokenTimeLeft']);

// POST route to handle the API call (ensure this uses POST)
Route::post('/car-key/coords', [CarKeyController::class, 'postDeviceDataRequest'])->name('car-key.post-coords');

// GET route to display the testing form/page
Route::get('/car-key-tester', [CarKeyController::class, 'showTesterView'])->name('car-key.show-tester'); 
// Added name for easier URL generation


Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');

// New routes for editing and updating
Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update'); // Or Route::patch

// New route for deleting
Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
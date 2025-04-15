<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarKeyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/car-key/token-status', [CarKeyController::class, 'tokenTimeLeft']);

// POST route to handle the API call (ensure this uses POST)
Route::post('/car-key/coords', [CarKeyController::class, 'postDeviceDataRequest'])->name('car-key.post-coords');

// GET route to display the testing form/page
Route::get('/car-key-tester', [CarKeyController::class, 'showTesterView'])->name('car-key.show-tester'); 
// Added name for easier URL generation

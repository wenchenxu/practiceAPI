<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarKeyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/car-key/token-status', [CarKeyController::class, 'tokenTimeLeft']);

Route::get('/car-key/coords', [CarKeyController::class, 'getCoordsInfo']);
    // ->middleware('auth:api'); // Ensure this route is protected by the API token
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarKeyController;

Route::get('/', function () {
    return view('welcome');
});

// test connection to the Car Key API
Route::get('/car-key/test', [CarKeyController::class, 'testConnection']);

// sanity check test
// Route::get('/simple-test', function() {return 'Simple test works!';});
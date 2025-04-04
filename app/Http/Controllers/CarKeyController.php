<?php

namespace App\Http\Controllers;

use App\Services\CarKeyService;
use Illuminate\Http\Request;

class CarKeyController extends Controller
{
    protected $carKeyService;

    public function __construct(CarKeyService $carKeyService)
    {
        $this->carKeyService = $carKeyService;
    }

    public function testConnection()
    {
        $response = $this->carKeyService->testConnection();
        
        return response()->json([
            'success' => !isset($response['error']),
            'response' => $response
        ]);
    }
}

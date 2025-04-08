<?php

namespace App\Http\Controllers;

use App\Services\CarKeyService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log; 

class CarKeyController extends Controller
{
    protected $carKeyService;

    public function __construct(CarKeyService $carKeyService)
    {
        $this->carKeyService = $carKeyService;
    }

    public function refreshToken(): JsonResponse
    {
        try {
            $this->carKeyService->fetchTokenFromUrl();  // Corrected method call
            return response()->json(['message' => 'Token refreshed successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function tokenTimeLeft(): JsonResponse
    {
        try {
            $result = $this->carKeyService->getTokenStatus();
            return response()->json(['data' => $result], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    protected function errorResponse(\Exception $e): JsonResponse
    {
        // You can customize the error response based on the exception type or code
        Log::error($e->getMessage(), ['exception' => $e]); // Log the error
        return response()->json(
            ['error' => $e->getMessage()],
            Response::HTTP_INTERNAL_SERVER_ERROR // Default to 500
        );
    }
}
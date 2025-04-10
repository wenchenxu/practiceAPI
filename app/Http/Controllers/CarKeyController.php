<?php

namespace App\Http\Controllers;

use App\Services\CarKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Symfony\Component\HttpFoundation\Response;


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

    public function getCoordsInfo(Request $request): JsonResponse
    {
        try {
            // --- Get coordinates from the request ---
            // Use $request->query() for GET parameters.
            // Provide default values (e.g., 0) or add validation if needed.
            $latitude = (float) $request->query('lat', 0); 
            $longitude = (float) $request->query('lon', 0); 

            // Optional: Add validation here to ensure lat/lon are provided and valid
            Log::debug("Controller received lat: " . $latitude . ", lon: " . $longitude);
            
            // --- Call the service method with the retrieved values ---
            // *** Double-check 'getCoordinatesData' exactly matches the method name in CarKeyService.php ***
            $coordinateData = $this->carKeyService->getCoordinatesData($latitude, $longitude);

            // Return the response from the service
            return response()->json(['data' => $coordinateData], Response::HTTP_OK);
        
        } catch (\Exception $e) {
            // Use the existing error handler
            return $this->errorResponse($e);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Services\CarKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Symfony\Component\HttpFoundation\Response;
use Illuminate\View\View;

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

    // --- NEW Method to display the tester view ---
    /**
     * Displays the view with the form to test the device coordinate posting.
     *
     * @return \Illuminate\View\View
     */
    public function showTesterView(): View
    {
        // This will look for a file named 'car-key-test.blade.php'
        // inside the 'resources/views/' directory.
        return view('car-key-test');
    }
    // --- End new method ---

    public function postDeviceDataRequest(Request $request): JsonResponse // Renamed method
    {
        try {
            Log::debug('Raw request content:', ['content' => $request->getContent()]);
            Log::debug('Request headers:', ['headers' => $request->headers->all()]); // Check Content-Type

            // --- Get deviceId from the request ---
            $deviceId = $request->json('deviceId'); // Use input() for POST body data

            // Log the retrieved value (or null if not found)
            Log::debug('Value retrieved for devId using $request->json():', ['deviceId' => $deviceId]);

            // --- Basic Validation: Ensure deviceId is provided ---
            if (empty($deviceId)) {
                Log::error('Device ID (devId) is missing from the request.');
                return response()->json(['error' => 'Missing required device ID.'], Response::HTTP_BAD_REQUEST); // Use :: syntax
            }
            // --- End Validation ---

            Log::debug("Controller received devId: " . $deviceId);

            // --- Call the updated service method ---
            // *** Ensure 'postDeviceData' exactly matches the method name in CarKeyService.php ***
            $apiResponse = $this->carKeyService->postDeviceData($deviceId); // Pass only deviceId

            // Return the response from the service
            return response()->json(['data' => $apiResponse], Response::HTTP_OK); // Use :: syntax

        } catch (\Exception $e) {
            // Use the existing error handler
            return $this->errorResponse($e);
        }
    }
}
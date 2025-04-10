<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CarKeyService
{
    protected $config;
    protected $token;
    protected $baseUrl;
    protected int $timeout = 30;
    protected $tokenExpiry;
    protected $tokenUrl;
    protected $keyControl;
    protected $keyCoordinates;
    protected $keyInfo;
    public function __construct()
    {
        $this->config = config('carkeys.api');
        $this->baseUrl = $this->config['base_url'];
        $this->timeout = $this->config['timeout'];
        $this->tokenUrl = $this->baseUrl . $this->config['endpoints']['token']; 
        $this->keyControl = $this->baseUrl . $this->config['endpoints']['keyControl'];
        $this->keyCoordinates = $this->baseUrl . $this->config['endpoints']['keyCoordinates'];
        $this->keyInfo = $this->baseUrl . $this->config['endpoints']['keyInfo'];

        if (empty($this->tokenUrl)) {
            throw new \RuntimeException('API token URL not configured');
        }

        $this->initializeToken();
    }

    protected function initializeToken()
    {
        $this->token = Cache::remember('car_key_api_token', $this->getTokenExpiryInSeconds(), function () {
            return $this->fetchTokenFromUrl();
        });

        $this->tokenExpiry = Cache::get('car_key_api_token_expiry');
    }

    public function fetchTokenFromUrl()
    {
        try {
            $response = Http::post($this->tokenUrl);  // Use the concatenated URL
            $response->throw();

            $data = $response->json();
            $this->token = $data['api_token'];

            // --- Add Detailed Logging ---
            $configExpiryValue = $this->config['token_expiry'] ?? 86400;
            Log::debug('Config token_expiry value: ' . $configExpiryValue . ' (Type: ' . gettype($configExpiryValue) . ')');

            $expirySeconds = $configExpiryValue;
            Log::debug('Casted expirySeconds value: ' . $expirySeconds . ' (Type: ' . gettype($expirySeconds) . ')');
            // --- End Detailed Logging ---

            $this->tokenExpiry = now()->addSeconds($this->config['token_expiry'] ?? 86400);
            Log::debug('Calculated tokenExpiry object type: ' . get_class($this->tokenExpiry)); // Check if it's a Carbon object

            $cacheExpiry = $this->tokenExpiry->copy()->subSeconds(60);
            Cache::put('car_key_api_token', $this->token, $cacheExpiry);
            Cache::put('car_key_api_token_expiry', $this->tokenExpiry, $this->tokenExpiry);

            return $this->token;

        } catch (\Exception $e) {
            // Log the raw response body if available, helpful for debugging structure issues
            $rawResponse = isset($response) ? $response->body() : 'No response object available';
            Log::error('Failed to fetch token from URL: ' . $e->getMessage(), [
                'url' => $this->tokenUrl,
                'response_body' => $rawResponse, // Log the raw body
                'exception' => $e // Log the full exception details
            ]);
            throw new \Exception('Failed to fetch token: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function makeRequest(string $endpoint, array $data = [], string $method = 'post', bool $retry = true)
    {
        if ($this->tokenNeedsRefresh()) {
            $this->fetchTokenFromUrl();
        }

        try {
            $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

            $response = Http::withToken($this->token)
                ->timeout($this->timeout)
                ->{$method}($url, $data);

            $response->throw();

            return $response->json();

        } catch (\Illuminate\Http\Client\RequestException $e) {
            if ($e->response->status() === 401 && $retry) {
                $this->fetchTokenFromUrl();
                return $this->makeRequest($endpoint, $data, $method, false);
            }

            Log::error("Car Key API Request Error: {$e->getMessage()} for {$url}", [
                'method' => $method,
                'data' => $data,
                'response' => $e->response ? $e->response->body() : null,
            ]);
            throw new \Exception("Car Key API Request Failed: {$e->getMessage()}", $e->getCode(), $e);

        } catch (\Exception $e) {
            Log::error("Car Key API General Error: {$e->getMessage()} for {$url}", [
                'method' => $method,
                'data' => $data
            ]);
            throw new \Exception("Car Key API Request Failed: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    protected function tokenNeedsRefresh(): bool
    {
        // --- Add Logging Here ---
        $cachedExpiry = Cache::get('car_key_api_token_expiry');
        Log::debug('Retrieved cachedExpiry value: ' . ($cachedExpiry ? $cachedExpiry->toDateTimeString() : 'null') . ' (Type: ' . gettype($cachedExpiry) . ')');
         if ($cachedExpiry && !($cachedExpiry instanceof \Carbon\Carbon)) {
             Log::warning('Cached token expiry is not a Carbon object!');
             // Decide how to handle this - maybe force refresh?
             // return true;
         }
        // --- End Logging ---

         // The original logic:
         if (!$cachedExpiry) { // Changed from $this->tokenExpiry to $cachedExpiry
             return true;
         }

        // Ensure comparison is happening with a Carbon object
        // This line might throw an error if $cachedExpiry is not a Carbon object after retrieval
        return (int) now()-> addSeconds(60)->gte($cachedExpiry);
    }

    protected function getTokenExpiryInSeconds(): int
    {
        $configExpiryValue = $this->config['token_expiry'] ?? 86400;
        // Log::debug('getTokenExpiryInSeconds - Config value: ' . $configExpiryValue . ' (Type: ' . gettype($configExpiryValue) . ')'); // Optional logging
        $expirySeconds = $configExpiryValue - 60;
        // Log::debug('getTokenExpiryInSeconds - Calculated value: ' . $expirySeconds . ' (Type: ' . gettype($expirySeconds) . ')'); // Optional logging
        return $expirySeconds;
    }

    // Commented out for future use.
    /* 
    protected function testEndpoint($endpointType, $params = [], $method = 'post')
    {
        try {
            $response = $this->makeRequest($this->config['endpoints'][$endpointType], $params, $method);
            return [
                'success' => true,
                'response' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    } */

    public function getTokenStatus()
    {
        $expiry = Cache::get('car_key_api_token_expiry');

        return [
            'token_exists' => (bool)Cache::has('car_key_api_token'),
            'expires_at' => $expiry ? $expiry->toDateTimeString() : null,
            'time_remaining' => $expiry ? $expiry->diff(now())->format('%h hours %i minutes') : null,
            'is_valid' => !$this->tokenNeedsRefresh()
        ];
    }

    public function tokenIsValid(): bool
    {
        return !$this->tokenNeedsRefresh();
    }

    public function getCoordinatesData(float $latitude, float $longitude)
    {
        $endpointPath = $this->config['endpoints']['keyCoordinates'] ?? '/api/getDeviceAddress'; // Get from config or use a default/hardcoded path
        $httpMethod = 'post';

        $requestData = [
            'data' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]
        ]; 
        
        Log::debug("Attempting POST to endpoint: " . $endpointPath . " with data: ", $requestData);

        // Use the existing makeRequest method
        $response = $this->makeRequest($endpointPath, $requestData, $httpMethod);

        // --- Parse the response based on API Documentation ---
        // Example 1: If response is like {"devices": ["id1", "id2"]}
        // return $response['devices'] ?? [];

        // Example 2: If response is like [{"id": "id1", "name": "car1"}, ...]
        // return array_map(function($device) { return $device['id']; }, $response ?? []);

        // Example 3: If response is just ["id1", "id2"]
        Log::debug("Received response: ", $response ?? []);
        return $response ?? [];
        // --- Adjust parsing based on actual response ---
    }
}
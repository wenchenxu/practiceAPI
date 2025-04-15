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
        Log::debug('--- Entered initializeToken ---');
        $cachedToken = Cache::get('car_key_api_token');
        Log::debug('initializeToken: Token from cache:', ['token' => $cachedToken ? substr($cachedToken, 0, 10) . '...' : 'null']);

        // This block fetches if cache is empty OR expired (Cache::remember handles expiry)
        $this->token = Cache::remember('car_key_api_token', $this->getTokenExpiryInSeconds(), function () {
            Log::debug('initializeToken: Cache empty or expired, calling fetchTokenFromUrl...');
            // It's crucial fetchTokenFromUrl returns the token if successful
            $fetchedToken = $this->fetchTokenFromUrl();
            Log::debug('initializeToken: Token fetched inside Cache::remember:', ['token' => $fetchedToken ? substr($fetchedToken, 0, 10) . '...' : 'null']);
            return $fetchedToken; // Ensure fetchTokenFromUrl returns the token
        });

        $this->tokenExpiry = Cache::get('car_key_api_token_expiry'); // Get potentially updated expiry
        Log::debug('initializeToken: Final token value set:', ['token' => $this->token ? substr($this->token, 0, 10) . '...' : 'null']);
        Log::debug('initializeToken: Final token expiry set:', ['expiry' => $this->tokenExpiry ? $this->tokenExpiry->toDateTimeString() : 'null']);
        Log::debug('--- Exiting initializeToken ---');
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
        Log::debug('--- Entered makeRequest ---');

        if ($this->tokenNeedsRefresh()) {
            Log::debug('makeRequest: Token needs refresh, attempting fetch...');
            try {
                $this->fetchTokenFromUrl();
            } catch (\Exception $e) {
                Log::error('makeRequest: Failed to refresh token: ' . $e->getMessage());
                throw $e;
            }
        }

        $currentTokenValue = $this->token; 
        Log::debug('makeRequest: Token value being sent:', ['token' => $currentTokenValue ? substr($currentTokenValue, 0, 10) . '...' : 'null']);

        // --- Ensure token value exists before proceeding ---
        if (empty($currentTokenValue)) {
            Log::error('makeRequest: Attempting to make request but token is empty!');
            throw new \Exception('API token is missing, cannot make request.');
        }
        // --- End check ---

        try {
            $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

            $response = Http::acceptJson()
                ->timeout($this->timeout)
                ->withHeaders([
                    'api_token' => $currentTokenValue // Send token in the custom 'api_token' header
                ])
                ->{$method}($url, $data);

            Log::debug('makeRequest: Raw API status code: ' . $response->status()); // Log status code

            // Important: Check for *your* API's success indicator BEFORE treating as success
            $responseData = $response->json();
            if ($response->successful() && isset($responseData['success']) && $responseData['success'] === false) {
                // Treat as failure even if HTTP status is 2xx based on API's own flag
                Log::error('makeRequest: API returned 2xx status but success=false flag.', ['response_body' => $responseData]);
                // Throw an exception based on the API's internal error
                throw new \Illuminate\Http\Client\RequestException($response->toPsrResponse()); // Create exception from response
            }

            // If HTTP status is not 2xx, this will throw an exception
            $response->throw();

            return $response->json();

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("makeRequest: RequestException status=" . ($e->response ? $e->response->status() : 'N/A') . " body=" . ($e->response ? $e->response->body() : 'N/A'));

            // Log details specific to the request exception (includes 4xx/5xx or thrown above)
            $responseBody = $e->response ? $e->response->body() : 'N/A';
            $responseStatus = $e->response ? $e->response->status() : 'N/A';
            Log::error("makeRequest: RequestException status={$responseStatus} body={$responseBody}");

            // No need for specific 401 retry logic here anymore, as the API doesn't seem to use standard 401
            // Simply re-throw the exception
            throw new \Exception("makeRequest: API Request Failed: {$e->getMessage()}", $e->getCode(), $e);

        } catch (\Exception $e) {
            Log::error("makeRequest: General Exception: {$e->getMessage()}");
            throw new \Exception("makeRequest: General API Request Failed: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    protected function tokenNeedsRefresh(): bool
    {

        $cachedExpiry = Cache::get('car_key_api_token_expiry');
        Log::debug('Retrieved cachedExpiry value: ' . ($cachedExpiry ? $cachedExpiry->toDateTimeString() : 'null') . ' (Type: ' . gettype($cachedExpiry) . ')');
         if ($cachedExpiry && !($cachedExpiry instanceof \Carbon\Carbon)) {
             Log::warning('Cached token expiry is not a Carbon object!');
             // Decide how to handle this - maybe force refresh?
             // return true;
         }
        
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

    public function postDeviceData(string $deviceId) // Renamed and changed signature
    {
        $endpointPath = $this->config['endpoints']['keyCoordinates'] ?? '/api/getDeviceAddress';
        // $endpointPath = $this->config['endpoints']['keyInfo'] ?? '/api/allDeviceInfo';
        $httpMethod = 'post'; // Correctly set to POST

        // --- Construct request data with deviceId ---
        $requestData = [
            'deviceId' => $deviceId // Send deviceId directly in the body
        ];
        // --- End request data construction ---

        Log::debug("Attempting POST to endpoint: " . $endpointPath . " with data: ", $requestData);

        // Use the existing makeRequest method - this handles the token header
        $response = $this->makeRequest($endpointPath, $requestData, $httpMethod);

        // --- Parse the response based on actual API Documentation ---
        Log::debug("Received response: ", $response ?? []);
        return $response ?? [];
        // --- Adjust parsing based on actual response ---
    }
}
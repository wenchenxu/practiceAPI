<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CarKeyService
{
    protected $baseUrl;
    protected $token;
    protected int $timeout = 30; // Default timeout in seconds, can be adjusted. Has to be typed as int or float.
    protected $authUrl;

    public function __construct()
    {
        $this->baseUrl = config('carkeys.api.base_url');
        $this->token = config('carkeys.api.token');
        $this->authUrl = config('carkeys.api.auth_url');
        $this->timeout = config('carkeys.api.timeout'); // Retrieve timeout from config or use default
    }

    public function sendRequest(string $endpoint, array $data = [], string $method = 'post')
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout($this->timeout)
                ->{$method}($this->baseUrl . $endpoint, $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Car Key API Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    // Basic test function
    public function testConnection()
    {
        return $this->sendRequest('/ping', [], 'get');
    }

    public function getToken()
    {
        // Check if we have a valid cached token
        /*
            There are some best practices here, to be implemented in the future. 
            1. Store the expiration time of the token in the cache, if possible (verify with API provider).
            2. Check token validity before using it. If it's close to expiration, refresh it.
            3. Use a background job to refresh the token before it expires, if the API supports it.
        */
        if (Cache::has('car_key_token')) {
            return Cache::get('car_key_token');
        }
        
        // Get new token
        $response = Http::post($this->authUrl, [
            'api_key' => config('carkeys.api.key'),
            'secret' => config('carkeys.api.secret')
        ]);
        
        $token = $response->json()['token'];
        
        // Store with 23-hour expiry (1 hour before actual expiry)
        Cache::put('car_key_token', $token, now()->addHours(23));
        
        return $token;
    }
}


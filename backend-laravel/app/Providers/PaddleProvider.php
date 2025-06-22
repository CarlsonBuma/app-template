<?php

namespace App\Providers;

use Exception;
use GuzzleHttp\Client;

class PaddleProvider
{
    /**
     * Paddle API Calls
     * https://developer.paddle.com/api-reference/overview
     *
     * @var Client|null
     */
    public ?Client $client;

    /**
     * Set Client
     */
    function __construct() 
    {
        $this->client = new Client([
            'verify' => !config('app.unsafe_http_request'),
            'base_uri' => config('paddle.url'),
        ]);
    }

    /**
     * Cancel subscription via API Request
     * 
     * Note:
     * Allows user to cancel Paddle price subscription at any time.
     * Consider cancel subscription at delete user!
     *
     * @param string $token
     * @return boolean
     */
    public function cancelSubscription(string $token): bool
    {
        $response = $this->client->request('POST', 'subscriptions/' . $token . '/cancel', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('paddle.api_key'),
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'effective_from' => 'immediately'
            ])
        ]);
        
        // Validate
        if($response->getStatusCode() !== 200) 
            throw new Exception($response->getStatusCode());
        
        return true;
    }
}

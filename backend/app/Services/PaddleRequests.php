<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;


class PaddleRequests
{
    private $client = null;

    /**
     * Setup Client API Call
     * 
     * Documentation:
     * https://developer.paddle.com/api-reference/overview
     * 
     * Important:
     * 'Https' protocoll disabled, on Sandbox Environment 
     * 
     */
    function __construct() 
    {
        $this->client = new Client([
            'verify' => env('CLIENT_ALLOW_HTTP_REQUEST', false) !== 'true',
            'base_uri' => env('PADDLE_API_URL'),
        ]);
    }

    /**
     * Cancel subscription via API Request
     * 
     * Note: 
     * Allows user to cancel Paddle price subscription at any time.
     *
     * @param string $token
     * @return boolean
     */
    public function cancelSubscription(string $token): bool
    {
        $response = $this->client->request('POST', 'subscriptions/' . $token . '/cancel', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('PADDLE_API_KEY'),
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

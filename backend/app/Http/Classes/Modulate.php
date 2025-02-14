<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\URL;


abstract class Modulate
{
    /**
     * Links sent via email by the backend should be modulated 
     * and redirected to the Single Page Application (SPA) for the app.
     *
     * @param String $route
     * @param Array|null $params
     * @return String
     */
    static public function signedLink(String $route, Array $params = null): String
    {
        // BaseURL (SERVER)
        $assignedBaseURL = URL::to('/') . '/api';
        
        // External URL (APP)
        $appBaseURL = env('CLIENT_URL');

        // Create Signed Laravel Route
        $signedVerificationLink = URL::temporarySignedRoute(
            $route,
            now()->addMinutes(10800),       // 1 Week
            $params, 
        );

        // Modulate VerificationLink for SPA URL (Client)
        // Replace $assignedBaseURL from $sigendVerificationLink by new $appBaseURL
        return str_replace($assignedBaseURL, $appBaseURL, $signedVerificationLink);
    }

    /**
     * Sanitize link
     *
     * @param string $rawPath
     * @return string|null
     */
    static public function sanitizeLink(string $rawPath = ''): string|null
    {
        $string = null;
        if(!$rawPath) return null;
        if( // Validate
            strpos($rawPath, 'http://') !== false 
            || strpos($rawPath, 'https://') !== false 
            || strpos($rawPath, 'www.') !== false
        ) {
            // Replace characters before 'https', 'http' by 'www.'
            $string = preg_replace('/^.*?(https?:\/\/|www\.)/i', '', $rawPath);
            $string = str_replace(array('http://','https://', 'www.', 'http://www.', 'https://www.'), '', $string);
            $string = 'www.' . $string;

            // Remove all special characters
            $string =  preg_replace("#[^a-zA-Z0-9_\-./:@%+~=?&\#]#", "", $string);   
        }

        return $string;
    }
}

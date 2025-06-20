<?php

namespace App\Providers;

use GuzzleHttp\Client;

class GeolocationProvider
{
    /**
     * Undocumented variable
     *
     * @var Client|null
     */
    public ?Client $client;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $googleMapsUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

    /**
     * Google Maps: Geolocation
     * https://developers.google.com/maps/documentation/geocoding/
     */
    function __construct()
    {
        $this->client = new Client([
            'verify' => !config('app.unsafe_http_request'),
        ]);
    }

    /**
     * Undocumented function
     *
     * @param string $address
     * @return array
     */
    public function getGeolocation(string $address): array
    {
        $apiAddress = $this->googleMapsUrl . urlencode($address) . '&key=' . config('google.api_key') . '&language=en';
        $response = $this->client->get($apiAddress);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error requesting geolocation data.');
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);
        if (empty($jsonData['results'])) {
            throw new \Exception($jsonData['error_message'] ?? 'Error fetching geolocation data.');
        }

        $googleData = $jsonData['results'][0];
        $location = [
            'place_id' => $googleData['place_id'],
            'lng' => number_format($googleData['geometry']['location']['lng'], 6),
            'lat' => number_format($googleData['geometry']['location']['lat'], 6),
            'address' => $googleData['formatted_address'],
        ];

        foreach ($googleData['address_components'] as $component) {
            foreach ($component['types'] as $type) {
                if ($type === 'administrative_area_level_1') {
                    $location['area'] = $component['long_name'];
                    $location['area_short'] = $component['short_name'];
                } elseif ($type === 'country') {
                    $location['country'] = $component['long_name'];
                    $location['country_short'] = $component['short_name'];
                } elseif ($type === 'postal_code') {
                    $location['zip_code'] = $component['long_name'];
                }
            }
        }

        return $location;
    }
}


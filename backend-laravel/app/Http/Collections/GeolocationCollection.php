<?php

namespace App\Http\Collections;


abstract class GeolocationCollection
{
    /**
     * Default collection
     *
     * @var array
     */
    public static $geoLocation = [
        '_type' => 'Collection $geolocation',
        'id' => 0,
        'place_id' => '',
        'lat' => 0,
        'lng' => 0,
        'address' => '',
        'area' => '',
        'area_short' => '',
        'country' => '',
        'country_short' => '',
        'zip_code' => '',
    ];

    /**
     * Render Geolocation Collection
     *
     * @param object|null $geolocation
     * @param boolean $showAddress
     * @return array
     */
    static public function render_geoLoaction(?object $geolocation, bool $showAddress): array
    {
        if(!$geolocation) return SELF::$geoLocation;
        return [
            '_type' => 'Collection $geolocation',
            'id' => $geolocation->id,
            'place_id' => $showAddress ? $geolocation->place_id : SELF::$geoLocation['place_id'],
            'lat' => $showAddress ? $geolocation->lat : SELF::$geoLocation['lat'],
            'lng' => $showAddress ? $geolocation->lng : SELF::$geoLocation['lng'],
            'address' => $showAddress ? $geolocation->address : SELF::$geoLocation['address'],
            'area' => $geolocation->area,
            'area_short' => $geolocation->area_short,
            'country' => $geolocation->country,
            'country_short' => $geolocation->country_short,
            'zip_code' => $geolocation->zip_code,
        ];
    }

    /**
     * Undocumented function
     *
     * @param float $locationLat
     * @param float $locationLng
     * @param float $centerLat
     * @param float $centerLng
     * @return void
     */
    static public function calculate_location_distance(float $locationLat, float $locationLng, float $centerLat, float $centerLng)
    {
        $earthRadius = 6371; // Radius of Earth in kilometers

        // Convert degrees to radians
        $latFrom = deg2rad($locationLat);
        $lngFrom = deg2rad($locationLng);
        $latTo = deg2rad($centerLat);
        $lngTo = deg2rad($centerLng);

        // Haversine formula
        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lngDelta / 2) * sin($lngDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Distance in km

        return round($distance, 2); // Return rounded distance in km
    }
}
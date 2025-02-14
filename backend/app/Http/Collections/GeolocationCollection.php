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
    static public function render_geoLoaction(object $geolocation = null, bool $showAddress): array
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
}
<?php

namespace App\Http\Collections;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

abstract class CockpitCollection
{
    /**
     * Default collection
     *
     * @var array
     */
    public static $cockpit = [
        '_type' => 'Collection $cockpit',
        'id' => 0,
        'is_public' => false,
        'name' => '',
        'avatar' => '',
        'about' => '',
        'contact' => '',
        'website' => '',
        'location' => null,
        'country_id' => null,
        'tags' => [],
    ];

    /**
     * User cockpit collection
     *  > Accessible if is_public
     *  > Or user is owner
     *
     * @param object|null $cockpit
     * @param boolean $isOwner
     * @return array
     */
    static public function render_cockpit(object $cockpit = null): array
    {
        if(!$cockpit) return SELF::$cockpit;
        
        $cockpitTags = $cockpit->tags;
        $geoLocation = $cockpit->location_id
            ? GeolocationCollection::render_geoLoaction($cockpit->belongs_to_location, $showAddress = true)
            : GeolocationCollection::$geoLocation;
        $avatarPath = $cockpit->avatar
            ? URL::to(Storage::url('cockpit')) . '/' . $cockpit->avatar
            : '';
        $country = $cockpit->country_id
            ? [
                'id' => $cockpit->country_id,
                'name' => $cockpit->belongs_to_country->name,
                'code' => $cockpit->belongs_to_country->code,
            ] : null;
        
        return [
            '_type' => 'Collection $cockpit',
            'id' => $cockpit->id,
            'is_public' => $cockpit->is_public,
            'name' => $cockpit->name,
            'avatar' => $avatarPath,
            'about' => $cockpit->about,
            'contact' => $cockpit->contact,
            'website' => $cockpit->website,
            'location' => $geoLocation,
            'country' => $country,
            'tags' => $cockpitTags,
        ];
    }
}
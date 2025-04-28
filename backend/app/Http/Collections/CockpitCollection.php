<?php

namespace App\Http\Collections;

use App\Http\Classes\FileStorage;
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
    static public function render_cockpit(?object $cockpit): array
    {
        if(!$cockpit) return SELF::$cockpit;
        $cockpitTags = $cockpit->tags;
        $geoLocation = $cockpit->location_id
            ? GeolocationCollection::render_geoLoaction($cockpit->belongs_to_location, $showAddress = true)
            : GeolocationCollection::$geoLocation;
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
            'avatar' => SELF::render_avatar_src($cockpit->avatar),
            'about' => $cockpit->about,
            'contact' => $cockpit->contact,
            'website' => $cockpit->website,
            'location' => $geoLocation,
            'country' => $country,
            'tags' => $cockpitTags,
        ];
    }

    /**
     * Undocumented function
     *
     * @param object|null $cockpit
     * @return array|null
     */
    static public function render_public_cockpit(?object $cockpit): ?array
    {
        if(!$cockpit) return null;
        
        return [
            '_type' => 'Collection $cockpit',
            'id' => $cockpit->id,
            'name' => $cockpit->name,
            'avatar' => SELF::render_avatar_src($cockpit->avatar),
            'about' => $cockpit->about,
            'contact' => $cockpit->contact,
            'website' => $cockpit->website,
        ];
    }

    /**
     * Undocumented function
     *
     * @param string|null $avatarSrc
     * @return string
     */
    static private function render_avatar_src(?string $avatarSrc): string
    {
        return $avatarSrc
            ? URL::to(Storage::url(FileStorage::$cockpitLocation)) . '/' . $avatarSrc
            : '';
    }
}
<?php

namespace App\Models;

use App\Models\Cockpit;
use App\Models\CockpitEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AppGeolocations extends Model
{
    use HasFactory;

    protected $table = 'public.app_geolocations';

    protected $fillable = [
        'place_id',
        'lng',
        'lat',
        'address',
        'country',
        'country_short',
        'area',
        'area_short',
        'zip_code'
    ];

    public function has_cockpits() {
        return $this->hasMany(Cockpit::class, 'location_id');
    }

    /**
     * Seed database
     * Add new geolocation entry
     *  > Google API Location
     *  > https://developers.google.com/maps/documentation/geocoding/start
     *
     * @param array $data
     * @return int|null
     */
    public function add_new_entry(array $data): int 
    {
        if(isset($data['place_id'])) {
            return $this->firstOrCreate([
                'place_id' => $data['place_id']
            ], [
                'lng' =>  $data['lng'],
                'lat' =>  $data['lat'],
                'address' =>  $data['address'] ?? null,
                'country' =>  $data['country'] ?? null,
                'country_short' =>  $data['country_short'] ?? null,
                'area' =>  $data['area'] ?? null,
                'area_short' =>  $data['area_short'] ?? null,
                'zip_code' =>  $data['zip_code'] ?? null
            ])->id;
        }

        return 0;
    }
}

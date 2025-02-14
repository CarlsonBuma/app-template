<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryDialSeeder extends Seeder
{
    public function run()
    {
        $str = file_get_contents('database/data/countries-and-dial.json');
        $countries = json_decode($str, true);

        $countryTable = DB::table('app_countries');
        foreach($countries as $country) {
            $countryTable->insert([
                'is_public' => true,
                'name' => $country['name'],
                'dial_code' => $country['dial_code'],
                'code' => $country['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

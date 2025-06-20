<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ISOCurrencySeeder extends Seeder
{
    public function run()
    {
        // Load the JSON file
        $str = file_get_contents(database_path('data/currencies.json'));
        $currencies = json_decode($str, true);

        $currencyTable = DB::table('app_currencies');

        foreach ($currencies as $code => $currency) {
            $currencyTable->insert([
                'is_public' => true, // Default all currencies to public
                'code' => $code,
                'name' => $currency['name'],
                'demonym' => $currency['demonym'],
                'major_single' => $currency['majorSingle'],
                'major_plural' => $currency['majorPlural'],
                'iso_num' => $currency['ISOnum'],
                'symbol' => $currency['symbol'],
                'symbol_native' => $currency['symbolNative'],
                'minor_single' => $currency['minorSingle'],
                'minor_plural' => $currency['minorPlural'],
                'iso_digits' => $currency['ISOdigits'],
                'decimals' => $currency['decimals'],
                'num_to_basic' => $currency['numToBasic'],
                'created_at' => now(), // Adding timestamps
                'updated_at' => now(),
            ]);
        }
    }
}

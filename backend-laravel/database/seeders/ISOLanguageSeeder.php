<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ISOLanguageSeeder extends Seeder
{
    public function run()
    {
        $str = file_get_contents('database/data/language-iso_639-1.json');
        $languages = json_decode($str, true);

        $userTable = DB::table('app_languages');
        foreach($languages as $index => $language) {
            $userTable->insert([
                'is_public' => true,
                '639-1' => $language['639-1'],
                '639-2' => $language['639-2'],
                'family' => $language['family'],
                'name' => $language['name'],
                'nativeName' => $language['nativeName'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

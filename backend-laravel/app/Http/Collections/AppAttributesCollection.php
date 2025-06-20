<?php

namespace App\Http\Collections;


abstract class AppAttributesCollection
{
    /**
     * Undocumented function
     *
     * @param object|null $currency
     * @return array|null
     */
    static public function render_currency(?object $currency): ?array
    {
        if(!$currency) return null;
        return [
            '_type' => 'Collection $AppCurrency',
            'id' => $currency->id,
            'is_public' => $currency->is_public,
            'code' => $currency->code,
            'name' => $currency->name,
            'demonym' => $currency->demonym,
            'major_single' => $currency->major_single,
            'major_plural' => $currency->major_plural,
            'iso_num' => $currency->iso_num,
            'symbol' => $currency->symbol,
            'symbol_native' => $currency->symbol_native,
            'minor_single' => $currency->minor_single,
            'minor_plural' => $currency->minor_plural,
            'iso_digits' => $currency->iso_digits,
            'decimals' => $currency->decimals,
            'num_to_basic' => $currency->num_to_basic,
            'created_at' => $currency->created_at,
            'updated_at' => $currency->updated_at,
        ];
    }
}
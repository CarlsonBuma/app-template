<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AppCurrencies extends Model
{
    use HasFactory;

    protected $table = 'public.app_currencies';
    protected $fillable = [
        'is_public',
        'code',
        'name',
        'demonym',
        'major_single',
        'major_plural',
        'iso_num',
        'symbol',
        'symbol_native',
        'minor_single',
        'minor_plural',
        'iso_digits',
        'decimals',
        'num_to_basic',
    ];
}

<?php

namespace App\Models;

use App\Models\Cockpit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AppCountries extends Model
{
    use HasFactory;

    protected $table = 'public.app_countries';
    protected $fillable = [
        'is_public',
        'name',
        'dial_code',
        'code'
    ];

    /**
     * Get all cockpits associated with this country.
     *
     * @return HasMany
     */
    public function has_cockpits(): HasMany
    {
        return $this->hasMany(Cockpit::class, 'country_id');
    }
}

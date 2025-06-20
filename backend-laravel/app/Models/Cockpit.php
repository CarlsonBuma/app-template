<?php

namespace App\Models;

use App\Models\User;
use App\Models\AppCountries;
use App\Models\AppGeolocations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cockpit extends Model
{
    use HasFactory;

    protected $table = 'public.cockpit';
    protected $fillable = [
        'user_id',          // Owner
        'is_public',        // Flag
        'name',
        'avatar',
        'location_id',
        'country_id',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];


    /**
     * Undocumented function
     *
     * @return BelongsTo
     */
    public function belongs_to_user(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Undocumented function
     *
     * @return BelongsTo
     */
    public function belongs_to_location(): BelongsTo 
    {
        return $this->belongsTo(AppGeolocations::class, 'location_id');
    }

    /**
     * Undocumented function
     *
     * @return BelongsTo
     */
    public function belongs_to_country(): BelongsTo 
    {
        return $this->belongsTo(AppCountries::class, 'country_id');
    }
}

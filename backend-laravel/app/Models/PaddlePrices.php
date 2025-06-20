<?php

namespace App\Models;

use App\Models\PaddleTransactions;
use App\Models\PaddleSubscriptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaddlePrices extends Model
{
    use HasFactory;

    protected $table = 'public.paddle_prices';
    protected $fillable = [
        
        'is_active',
        'access_token',     // $Flag: Access
        'limit_quantity',
        'limit_storage',
        'duration_months',  

        'price_token',
        'product_token',

        'name',
        'description',
        'type',
        'price',
        'tax_mode',
        'currency_code',
        'billing_interval',
        'billing_frequency',
        'trial_interval',
        'trial_frequency',
          
        'status',           // $Flag: Active vs. archived
        'message',
    ];

    /**
     * Undocumented function
     *
     * @return HasMany
     */
    public function has_subscriptions(): HasMany
    {
        return $this->hasMany(PaddleSubscriptions::class, 'price_id');
    }

    /**
     * Undocumented function
     *
     * @return HasMany
     */
    public function has_transactions(): HasMany
    {
        return $this->hasMany(PaddleTransactions::class, 'price_id');
    }

    /**
     * Undocumented function
     *
     * @return HasMany
     */
    public function has_access(): HasMany
    {
        return $this->hasMany(UserAccess::class, 'price_id');
    }
}

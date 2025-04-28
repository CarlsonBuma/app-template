<?php

namespace App\Models;

use App\Models\User;
use App\Models\PaddlePrices;
use App\Models\PaddleSubscriptions;
use App\Models\UserAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Seed by Paddle Transactions
 */
class PaddleTransactions extends Model
{
    use HasFactory;

    protected $table = 'public.paddle_transactions';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'transaction_token',
        'customer_token',
        'price_id',
        'total',
        'tax',
        'currency_code',

        // Access
        'quantity',
        'expiration_date',
        'access_added',
        'is_verified',
        'canceled_at',
        'status',
        'message',
    ];

    protected $casts = [
        'total' => 'float:2',
        'tax' => 'float:2',
        'expiration_date' => 'date:Y-m-d',
        'canceled_at' => 'datetime',
    ];

    public function belongs_to_user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function belongs_to_subscription() {
        return $this->belongsTo(PaddleSubscriptions::class, 'subscription_id');
    }

    public function belongs_to_price() {
        return $this->belongsTo(PaddlePrices::class, 'price_id');
    }

    public function has_access() {
        return $this->hasMany(UserAccess::class, 'transaction_id');
    }
}

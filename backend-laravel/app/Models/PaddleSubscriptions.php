<?php

namespace App\Models;

use App\Models\PaddlePrices;
use App\Models\PaddleTransactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Seed by Paddle Transactions
 */
class PaddleSubscriptions extends Model
{
    use HasFactory;

    protected $table = 'public.paddle_subscriptions';

    protected $fillable = [
        'user_id',
        'price_id',
        'subscription_token',
        'started_at',
        'canceled_at',
        'paused_at',
        'status',
        'message',
    ];

    protected $casts = [
        'started_at' => 'date:Y-m-d',
        'canceled_at' => 'date:Y-m-d',
        'paused_at' => 'date:Y-m-d',
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
    public function belongs_to_price(): BelongsTo
    {
        return $this->belongsTo(PaddlePrices::class, 'price_id');
    }

    /**
     * Undocumented function
     *
     * @return HasMany
     */
    public function has_transactions(): HasMany
    {
        return $this->hasMany(PaddleTransactions::class, 'subscription_id');
    }
}

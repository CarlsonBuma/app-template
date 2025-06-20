<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaddleTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccess extends Model
{
    use HasFactory;

    protected $table = 'public.user_access';
    protected $fillable = [
        'user_id',
        'price_id',
        'transaction_id',
        'is_active',
        'access_token',
        'quantity', 
        'limit_storage',
        'expiration_date',
        'status',
        'message',
    ];

    protected $casts = [
        'expiration_date' => 'date:Y-m-d',
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
    public function belongs_to_transaction(): BelongsTo 
    {
        return $this->belongsTo(PaddleTransactions::class, 'transaction_id');
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
}

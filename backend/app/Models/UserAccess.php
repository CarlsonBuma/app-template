<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaddleTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAccess extends Model
{
    use HasFactory;

    protected $table = 'public.user_access';

    protected $fillable = [
        'user_id',
        'transaction_id',
        'is_active',
        'access_token',
        'quantity',
        'expiration_date',
        'status',
        'message',
    ];

    protected $casts = [
        'expiration_date' => 'date:Y-m-d',
    ];

    public function belongs_to_user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function belongs_to_transaction() {
        return $this->belongsTo(PaddleTransactions::class, 'transaction_id');
    }
}

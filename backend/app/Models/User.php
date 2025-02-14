<?php

namespace App\Models;

use App\Models\Cockpit;
use App\Models\UserAccess;
use App\Models\PaddleTransactions;
use Laravel\Passport\HasApiTokens;
use App\Models\PaddleSubscriptions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'public.users';

    protected $fillable = [
        'name',
        'avatar',
        'email',
        'password',
        'token',
        'email_verified_at',    // Flag
        'archived'              // Flag
    ];

    protected $hidden = [
        'password',
        'token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'archived' => 'datetime',
    ];

    public function has_cockpit() {
        return $this->hasOne(Cockpit::class, 'user_id');
    }

    public function has_subsciptions() {
        return $this->hasMany(PaddleSubscriptions::class, 'user_id');
    }

    public function has_transactions() {
        return $this->hasMany(PaddleTransactions::class, 'user_id');
    }

    public function has_access() {
        return $this->hasMany(UserAccess::class, 'user_id');
    }
}

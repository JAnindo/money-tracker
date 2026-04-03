<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    // A user has many wallets
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    // Total balance across all wallets
    public function getTotalBalanceAttribute(): float
    {
        return $this->wallets->sum('balance');
    }
}
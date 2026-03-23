<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    // Fields we can fill (security feature)
    protected $fillable = ['name', 'email'];

    /**
     * A user has many wallets
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Calculate total balance across all wallets
     */
    public function getTotalBalanceAttribute(): float
    {
        return $this->wallets->sum('balance');
    }
}
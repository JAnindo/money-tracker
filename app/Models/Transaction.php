<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['wallet_id', 'type', 'amount', 'description'];

    /**
     * A transaction belongs to one wallet
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Auto-update wallet balance when transaction is created
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            $wallet = $transaction->wallet;
            
            if ($transaction->type === 'income') {
                $wallet->balance += $transaction->amount;  // Add money
            } else {
                $wallet->balance -= $transaction->amount;  // Subtract money
            }
            
            $wallet->save();
        });
    }
}
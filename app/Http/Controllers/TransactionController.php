<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * Create a new transaction (POST /api/transactions)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);

        // Check if expense exceeds balance
        if ($validated['type'] === 'expense') {
            $wallet = Wallet::find($validated['wallet_id']);
            
            if ($wallet->balance < $validated['amount']) {
                return response()->json([
                    'message' => 'Insufficient balance for this expense',
                    'current_balance' => $wallet->balance,
                    'attempted_amount' => $validated['amount']
                ], 400); // 400 = Bad Request
            }
        }

        // Create transaction (auto-updates wallet balance via model boot)
        $transaction = Transaction::create($validated);

        return response()->json([
            'message' => 'Transaction created successfully',
            'transaction' => $transaction,
            'wallet_new_balance' => $transaction->wallet->fresh()->balance
        ], 201);
    }
}
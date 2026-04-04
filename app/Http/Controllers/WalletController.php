<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    // Create a new wallet (POST /api/wallets)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name'    => 'required|string|max:255',
        ]);

        $wallet = Wallet::create([
            'user_id' => $validated['user_id'],
            'name'    => $validated['name'],
            'balance' => 0.00,
        ]);

        return response()->json([
            'message' => 'Wallet created successfully',
            'wallet'  => $wallet
        ], 201);
    }

    // Get single wallet with transactions (GET /api/wallets/{id})
    public function show($id): JsonResponse
    {
        $wallet = Wallet::with('transactions')->find($id);

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        return response()->json([
            'wallet' => [
                'id'           => $wallet->id,
                'name'         => $wallet->name,
                'balance'      => $wallet->balance,
                'user_id'      => $wallet->user_id,
                'transactions' => $wallet->transactions->map(function ($transaction) {
                    return [
                        'id'          => $transaction->id,
                        'type'        => $transaction->type,
                        'amount'      => $transaction->amount,
                        'description' => $transaction->description,
                        'created_at'  => $transaction->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ]
        ]);
    }

    // Delete a wallet (DELETE /api/wallets/{id})
    public function destroy($id): JsonResponse
    {
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $wallet->delete();

        return response()->json(['message' => 'Wallet deleted successfully']);
    }
}
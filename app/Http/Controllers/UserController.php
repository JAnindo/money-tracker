<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Create a new user (POST /api/users)
     */
    public function store(Request $request): JsonResponse
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        // Create the user
        $user = User::create($validated);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201); // 201 = Created
    }

    /**
     * Get user profile with wallets (GET /api/users/{id})
     */
    public function show($id): JsonResponse
    {
        // Find user and load their wallets
        $user = User::with('wallets')->find($id);

        // If user not found
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404); // 404 = Not Found
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'total_balance' => $user->total_balance,
                'wallets' => $user->wallets->map(function ($wallet) {
                    return [
                        'id' => $wallet->id,
                        'name' => $wallet->name,
                        'balance' => $wallet->balance,
                    ];
                }),
            ]
        ]);
    }
}
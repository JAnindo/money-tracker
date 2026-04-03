<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Register a new user (POST /api/register)
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']), // Encrypt password
        ]);

        // Create token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Account created successfully',
            'user'         => $user,
            'access_token' => $token,  // User needs this to access API
            'token_type'   => 'Bearer',
        ], 201);
    }

    // Login (POST /api/login)
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login successful',
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    // Logout (POST /api/logout)
    public function logout(Request $request): JsonResponse
    {
        // Delete current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    // Get user profile (GET /api/profile)
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load('wallets');

        return response()->json([
            'user' => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'total_balance' => $user->total_balance,
                'wallets'       => $user->wallets->map(function ($wallet) {
                    return [
                        'id'      => $wallet->id,
                        'name'    => $wallet->name,
                        'balance' => $wallet->balance,
                    ];
                }),
            ]
        ]);
    }
}
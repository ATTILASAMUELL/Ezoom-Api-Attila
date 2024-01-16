<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('token-name')->plainTextToken;

            return response([
                'token' => $token,
                'name' => $user->name,
                'email' => $user->email
            ], 201);
        } catch (\Exception $e) {
            return response(['message' => 'Error during registration'], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response(['message' => 'Invalid credentials'], 401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('token-name')->plainTextToken;

            return response([
                'token' => $token,
                'name' => $user->name,
                'email' => $user->email
            ], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error during login'], 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();

            return response(['message' => 'Logged out'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error during logout'], 500);
        }
    }
}

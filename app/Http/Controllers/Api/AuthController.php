<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);
        $accessToken = $user->createToken('access_token', ['access-api'], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));

        return response()->json([
            'jwt_token' => $token,
            'access_token' => $accessToken->plainTextToken,
            'access_token_expiration' => config('sanctum.ac_expiration'), // Ensure this is in minutes
            'refresh_token' => $refreshToken->plainTextToken,
            'refresh_token_expiration' => config('sanctum.rt_expiration'), // Ensure this is in minutes
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();
        $accessToken = $user->createToken('access_token', ['access-api'], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));

        return response()->json([
            'jwt_token' => $token,
            'access_token' => $accessToken->plainTextToken,
            'access_token_expiration' => config('sanctum.ac_expiration'), // Ensure this is in minutes
            'refresh_token' => $refreshToken->plainTextToken,
            'refresh_token_expiration' => config('sanctum.rt_expiration'), // Ensure this is in minutes
        ]);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $accessToken = $user->createToken('access_token', ['access-api'], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'access_token_expiration' => config('sanctum.ac_expiration'), // Ensure this is in minutes
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validation des données de l'inscription
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users',
                    'regex:/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/', // Regex pour l'email
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',  // Vérifie que password_confirmation correspond
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',  // Regex pour le mot de passe sécurisé
                ],
            ]);
    
            // Récupérer le rôle par défaut (par exemple "user")
            $role = Role::where('role_name', 'user')->first();
    
            // Création de l'utilisateur
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $role->id,
            ]);
    
            // Génération du token API
            $token = $user->createToken('access_token', ['*'], Carbon::now()->addHours(24))->plainTextToken;
    
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $role->role_name,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }    
    
    public function login(Request $request)
    {
        // Validation et Authentification
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        if (!Auth::attempt($data)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        
        // Génération du token API comme sécurité supplémentaire
        $user = Auth::user();
        $token = $user->createToken('access_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->role_name,
            ],
        ]);
    }
    

    public function userInfo(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'role' => $request->user()->role->role_name,
        ]);
    }

// AuthController.php
public function logout(Request $request)
{
    // Récupère le token de l'utilisateur connecté
    $user = $request->user();
    
    // Révoque le token actif
    $user->currentAccessToken()->delete();

    return response()->json(['message' => 'Successfully logged out']);
}

}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Voir les informations de l'utilisateur authentifié
    public function show(Request $request)
    {
        $user = $request->user();  // Récupérer l'utilisateur à partir du token
        return response()->json($user);
    }

    // Mise à jour des informations de l'utilisateur authentifié
    public function update(Request $request)
    {
        $user = $request->user();  // Récupérer l'utilisateur à partir du token

        // Valider uniquement les champs présents dans la requête
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        // Mettre à jour uniquement les champs présents dans la requête
        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }
}

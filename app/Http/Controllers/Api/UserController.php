<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    // Liste des utilisateurs (admin only)
    public function index()
    {
        $users = User::with('role')->get();
        return response()->json(['data' => $users]);
    }

    // Détails d'un utilisateur (admin only)
    public function show($id)
    {
        $user = User::with('role')->find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        return response()->json($user);
    }

    // Créer un nouvel utilisateur (admin only)
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Empêcher la création d'un autre admin si l'utilisateur n'est pas admin
        $authenticatedUser = $request->user();
        $adminRoleId = Role::where('role_name', 'admin')->first()->id;
        if ($request->role_id == $adminRoleId && $authenticatedUser->role->role_name != 'admin') {
            return response()->json(['message' => 'Non autorisé à créer des utilisateurs administrateurs'], 403);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return response()->json(['message' => 'Utilisateur créé avec succès', 'user' => $user], 201);
    }

    // Mettre à jour un utilisateur (admin only)
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Empêcher la modification des administrateurs
        if ($user->role->role_name == 'admin') {
            return response()->json(['message' => 'Impossible de modifier les administrateurs'], 403);
        }

        // Valider les données
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6',
            'role_id' => 'sometimes|required|exists:roles,id',
        ]);

        // Mettre à jour les champs fournis
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('role_id')) {
            $user->role_id = $request->role_id;
        }

        $user->save();

        return response()->json(['message' => 'Utilisateur mis à jour avec succès', 'user' => $user]);
    }

    // Supprimer un utilisateur (admin only)
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Empêcher la suppression des administrateurs
        if ($user->role->role_name == 'admin') {
            return response()->json(['message' => 'Impossible de supprimer les administrateurs'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}

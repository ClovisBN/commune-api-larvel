<?php

namespace App\Http\Controllers;

use App\Models\Grievance;
use App\Models\GrievanceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrievanceController extends Controller
{
    // Liste des doléances de l'utilisateur
    public function index()
    {
        $user = Auth::user();
        $grievances = Grievance::with(['messages', 'status'])
            ->where('user_id', $user->id)
            ->get();
        
        return response()->json(['data' => $grievances]);
    }

    // Liste de toutes les doléances (admin)
    public function allGrievances()
    {
        $grievances = Grievance::with(['messages', 'status'])->get();
        return response()->json(['data' => $grievances]);
    }

    // Créer une nouvelle doléance avec statut par défaut
    public function store(Request $request)
    {
        $request->validate([
            'title_grievance' => 'required|string|max:255',
        ]);

        // Récupérer le statut par défaut (par exemple, "open")
        $defaultStatus = GrievanceStatus::where('name', 'open')->first();

        if (!$defaultStatus) {
            return response()->json(['message' => 'Statut par défaut "open" non trouvé'], 500);
        }

        $grievance = Grievance::create([
            'title_grievance' => $request->title_grievance,
            'user_id' => Auth::id(),
            'status_id' => $defaultStatus->id,
        ]);

        return response()->json(['message' => 'Doléance créée avec succès', 'data' => $grievance], 201);
    }

    // Clôturer une doléance
    public function close($id)
    {
        $grievance = Grievance::find($id);
        
        if (!$grievance) {
            return response()->json(['message' => 'Doléance introuvable'], 404);
        }

        $closedStatus = GrievanceStatus::where('name', 'closed')->first();
        if (!$closedStatus) {
            return response()->json(['message' => 'Statut "closed" non trouvé'], 500);
        }

        if ($grievance->status_id == $closedStatus->id) {
            return response()->json(['message' => 'La doléance est déjà fermée'], 400);
        }

        $grievance->update(['status_id' => $closedStatus->id]);

        return response()->json(['message' => 'Doléance fermée avec succès']);
    }

    // Liste des messages d'une doléance
    public function messages($id)
    {
        $grievance = Grievance::with('messages.user')->find($id);

        if (!$grievance) {
            return response()->json(['message' => 'Doléance introuvable'], 404);
        }

        return response()->json(['data' => $grievance->messages]);
    }
}

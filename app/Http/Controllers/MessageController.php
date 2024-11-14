<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Grievance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Envoyer un message dans une doléance
    public function store(Request $request, $grievance_id)
    {
        $grievance = Grievance::find($grievance_id);

        if (!$grievance || $grievance->status === 'closed') {
            return response()->json(['message' => 'Doléance introuvable ou fermée'], 404);
        }

        $request->validate(['content_message' => 'required|string']);

        $message = Message::create([
            'grievance_id' => $grievance_id,
            'user_id' => Auth::id(),
            'content_message' => $request->content_message,
        ]);

        return response()->json(['message' => 'Message envoyé avec succès', 'data' => $message], 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return response()->json(['data' => $documents]);
    }

    public function store(Request $request)
    {
        // Validation des données envoyées par le frontend
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'questions' => 'nullable|array',
        ]);
    
        // Récupérer l'utilisateur authentifié
        $user = $request->user();
    
        // Créer le document en l'associant à l'utilisateur
        $document = Document::create([
            'name' => $request->name,
            'description' => $request->description,
            'questions' => $request->questions,
            'user_id' => $user->id,  // Associer l'utilisateur au document
        ]);
    
        // Retourner le document avec l'ID
        return response()->json($document, 201);
    }
    

    public function show($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        return response()->json($document);
    }

    public function update(Request $request, $id)
    {
        try {
            $document = Document::find($id);
    
            if (!$document) {
                return response()->json(['message' => 'Document not found'], 404);
            }
    
            // Supprimer l'ancien screenshot s'il existe et qu'un nouveau est fourni
            if ($request->has('screenshot')) {
                if ($document->screenshot_path) {
                    Storage::disk('public')->delete($document->screenshot_path);
                }
    
                // Stocker le nouveau screenshot
                $image = $request->input('screenshot'); // base64 string
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = $document->id . '_screenshot.png';
                $filePath = 'screenshots/' . $imageName;
                Storage::disk('public')->put($filePath, base64_decode($image));
                $document->screenshot_path = $filePath;
            }
    
            // Mettre à jour les autres informations du document
            $document->update([
                'name' => $request->name,
                'description' => $request->description,
                'questions' => $request->questions,
                'screenshot_path' => $document->screenshot_path, // Assurez-vous de mettre à jour le chemin du screenshot
            ]);
    
            return response()->json($document);
        } catch (\Exception $e) {
            Log::error('Error saving document: ' . $e->getMessage()); // Enregistrer l'erreur
            return response()->json(['message' => 'Error saving document', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function destroy($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        $document->delete();

        return response()->json(['message' => 'Document deleted']);
    }
}

<?php
// app/Http/Controllers/ArticleController.php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return response()->json(['data' => $articles]);
    }

    public function store(Request $request)
    {
        // Validation des données envoyées par le frontend
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'components' => 'nullable|array',
        ]);
    
        // Récupérer l'utilisateur authentifié
        $user = $request->user();
    
        // Créer l'article en l'associant à l'utilisateur
        $article = Article::create([
            'title' => $request->title,
            'description' => $request->description,
            'components' => $request->components,
            'user_id' => $user->id,  // Associer l'utilisateur à l'article
        ]);
    
        // Retourner l'article avec l'ID
        return response()->json($article, 201);
    }
    
    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }

    public function update(Request $request, $id)
    {
        try {
            $article = Article::find($id);
    
            if (!$article) {
                return response()->json(['message' => 'Article not found'], 404);
            }

            // Mettre à jour les informations de l'article
            $article->update([
                'title' => $request->title,
                'description' => $request->description,
                'components' => $request->components,
            ]);
    
            return response()->json($article);
        } catch (\Exception $e) {
            Log::error('Error saving article: ' . $e->getMessage());
            return response()->json(['message' => 'Error saving article', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted']);
    }
}

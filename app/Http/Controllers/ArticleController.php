<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('status')->get();
        return response()->json(['data' => $articles]);
    }

    public function store(Request $request)
    {
        // Validation des données envoyées par le frontend
        $request->validate([
            'title_article' => 'required|string|max:255',
            'description_article' => 'nullable|string|max:255',
            'content_article' => 'nullable|array',
        ]);

        // Récupérer l'utilisateur authentifié
        $user = $request->user();

        $statusId = 1;

        // Créer l'article en l'associant à l'utilisateur
        $article = Article::create([
            'title_article' => $request->title_article,
            'description_article' => $request->description_article,
            'content_article' => $request->content_article,
            'user_id' => $user->id,
            'status_id' => $statusId,
        ]);

        // Retourner l'article avec l'ID
        return response()->json($article, 201);
    }

    public function show($id)
    {
        $article = Article::with('status')->find($id);

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

            // Valider les données
            $request->validate([
                'title_article' => 'sometimes|required|string|max:255',
                'description_article' => 'sometimes|nullable|string|max:255',
                'content_article' => 'sometimes|nullable|array',
                'status_id' => 'sometimes|required|exists:article_statuses,id',
            ]);

            // Mettre à jour les informations de l'article
            $article->update($request->only([
                'title_article',
                'description_article',
                'content_article',
                'status_id',
            ]));

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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Get comments for an article
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function index(Article $article): JsonResponse
    {
        try {
            $comments = $article->comments()
                ->where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'name' => $comment->name,
                        'message' => $comment->message,
                        'created_at' => $comment->created_at->toIso8601String(),
                        'created_at_formatted' => $comment->created_at->format('d M Y H:i')
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Comentarios obtenidos exitosamente',
                'data' => $comments,
                'total' => $comments->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener comentarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new comment
     *
     * @param Request $request
     * @param Article $article
     * @return JsonResponse
     */
    public function store(Request $request, Article $article): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string|max:1000',
            ]);

            $comment = Comment::create([
                'article_id' => $article->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => $validated['message'],
                'is_approved' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comentario agregado exitosamente',
                'data' => [
                    'id' => $comment->id,
                    'name' => $comment->name,
                    'message' => $comment->message,
                    'created_at' => $comment->created_at->toIso8601String(),
                    'created_at_formatted' => $comment->created_at->format('d M Y H:i')
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific comment
     *
     * @param Article $article
     * @param Comment $comment
     * @return JsonResponse
     */
    public function show(Article $article, Comment $comment): JsonResponse
    {
        try {
            // Verify the comment belongs to the article
            if ($comment->article_id !== $article->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comentario no encontrado para este artículo'
                ], 404);
            }

            if (!$comment->is_approved) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comentario no disponible'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Comentario obtenido exitosamente',
                'data' => [
                    'id' => $comment->id,
                    'name' => $comment->name,
                    'message' => $comment->message,
                    'created_at' => $comment->created_at->toIso8601String(),
                    'created_at_formatted' => $comment->created_at->format('d M Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

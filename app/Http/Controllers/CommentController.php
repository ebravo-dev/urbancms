<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        Comment::create([
            'article_id' => $article->id,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'is_approved' => true,
        ]);

        return redirect()->back()->with('success', 'Comentario agregado exitosamente');
    }

    /**
     * Update the approval status of the comment.
     */
    public function toggleApproval(Comment $comment)
    {
        $comment->update([
            'is_approved' => !$comment->is_approved,
        ]);

        return redirect()->back()->with('success', 'Estado de aprobación actualizado');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Comentario eliminado exitosamente');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogCommentRequest;
use App\Models\Post;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    /**
     * Store a new comment for a blog post.
     *
     * @param  BlogCommentRequest  $request
     * @param  Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BlogCommentRequest $request, Post $post)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Hanya user terdaftar yang bisa berkomentar.'], 403);
        }
        $comment = new BlogComment();
        $comment->post_id = $post->id;
        $comment->comment = $request->comment;
        $comment->user_id = auth()->id();
        $comment->save();
        $formattedDate = $comment->created_at->format('d M Y H:i');
        $name = auth()->user()->name;
        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'name' => $name,
                'text' => $comment->comment,
                'date' => $formattedDate,
                'user_id' => $comment->user_id
            ]
        ]);
    }

    /**
     * Get comments for a blog post.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments(Post $post)
    {
        $comments = $post->comments()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'name' => $comment->user ? $comment->user->name : ($comment->guest_name ?: 'Guest'),
                    'text' => $comment->comment,
                    'date' => $comment->created_at->format('d M Y H:i'),
                    'user_id' => $comment->user_id
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }

    /**
     * Hapus komentar blog (khusus admin).
     */
    public function destroy(BlogComment $comment)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $comment->delete();
        return response()->json(['success' => true]);
    }
} 
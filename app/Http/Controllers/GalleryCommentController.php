<?php

namespace App\Http\Controllers;

use App\Http\Requests\GalleryCommentRequest;
use App\Models\Gallery;
use App\Models\GalleryComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryCommentController extends Controller
{
    /**
     * Store a new comment for a gallery.
     *
     * @param  GalleryCommentRequest  $request
     * @param  Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GalleryCommentRequest $request, Gallery $gallery)
    {
        $comment = new GalleryComment();
        $comment->gallery_id = $gallery->id;
        $comment->comment = $request->comment;
        
        if (Auth::check()) {
            $comment->user_id = Auth::id();
        } else {
            $comment->guest_name = 'Guest';
        }

        $comment->save();

        // Format tanggal untuk respons
        $formattedDate = $comment->created_at->format('d M Y H:i');
        
        // Siapkan nama untuk ditampilkan
        $name = Auth::check() ? Auth::user()->name : 'Guest';

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
     * Get comments for a gallery.
     *
     * @param  Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments(Gallery $gallery)
    {
        $comments = $gallery->comments()
            ->with('user')
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
} 
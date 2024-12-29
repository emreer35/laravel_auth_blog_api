<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Post $post)
    {
        $fields = $request->validated();
        $comment = $post->comment()->create([
            'comment' => $fields['comment'],
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'message' => 'Comment successfuly added.',
            'comment' => $comment
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment,)
    {
        if ($post->id !== $comment->post_id) {
            return response()->json([
                'error' => 'The Comment isn`t the Post`s'
            ], 403);
        }
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'You do not have permission to delete this comment.'
            ], 403);
        }
        $comment->delete();
        return response()->noContent();
    }
}

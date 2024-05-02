<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = new Comment(['content' => $request->input('content')]);
        $comment->user()->associate(Auth::user());
        $comment->post()->associate($post);
        if (empty($post->id)) {
            \Log::error("Post ID is missing");
            abort(500, "Post ID is missing");
        }
        $comment->save();

        return redirect()->route('posts.show', $post)->with('success', 'Comment added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        if (Gate::allows('delete-comment', $comment)) {
            $comment->delete();
            return redirect()->route('show', $post)->with('success', 'Comment deleted successfully.');
        } else {
            return redirect()->route('show', $post)->with('error', 'You are not authorised to delete this comment.');
        }
    }

    public function edit
}

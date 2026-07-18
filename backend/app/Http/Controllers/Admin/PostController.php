<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'topic'])->orderByDesc('created_at')->paginate(30);

        return view('admin.posts.index', compact('posts'));
    }

    public function update(Request $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $post->update([
            'body' => $validated['body'],
            'edited_at' => now(),
        ]);

        return redirect()->route('topics.show', $post->topic)->with('status', 'Сообщение изменено.');
    }

    public function destroy(Request $request, Post $post)
    {
        $user = $request->user();

        if ($user->id !== $post->user_id && ! $user->isModerator()) {
            abort(403);
        }

        $post->delete();

        return back()->with('status', 'Сообщение удалено.');
    }
    }
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

    public function toggleHide(Post $post)
    {
        $post->update(['is_hidden' => ! $post->is_hidden]);

        return back()->with('status', $post->is_hidden ? 'Сообщение скрыто.' : 'Сообщение возвращено.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab') === 'moderation' ? 'moderation' : 'all';

        $query = Post::with(['user', 'topic']);

        if ($tab === 'moderation') {
            $query->whereIn('moderation_status', ['pending', 'rejected'])
                ->orderByDesc('confidence_score');
        } else {
            $query->orderByDesc('created_at');
        }

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $posts = $query->paginate(25)->withQueryString();

        $counts = [
            'all' => Post::count(),
            'moderation' => Post::whereIn('moderation_status', ['pending', 'rejected'])->count(),
        ];

        return view('admin.posts.index', compact('posts', 'counts', 'tab'));
    }

    public function approve(Post $post)
    {
        $post->update(['moderation_status' => 'approved']);

        return back()->with('status', 'Сообщение опубликовано.');
    }

    public function reject(Post $post)
    {
        $post->update(['moderation_status' => 'rejected']);

        return back()->with('status', 'Сообщение скрыто.');
    }
}

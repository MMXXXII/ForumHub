<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'queue');

        $query = Post::with(['user', 'topic']);

        match ($filter) {
            'rejected' => $query->where('moderation_status', 'rejected'),
            'pending' => $query->where('moderation_status', 'pending'),
            default => $query->whereIn('moderation_status', ['pending', 'rejected']),
        };

        $posts = $query->orderByDesc('confidence_score')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending' => Post::where('moderation_status', 'pending')->count(),
            'rejected' => Post::where('moderation_status', 'rejected')->count(),
            'approved' => Post::where('moderation_status', 'approved')->count(),
        ];

        return view('admin.moderation.index', compact('posts', 'counts', 'filter'));
    }

    public function approve(Post $post)
    {
        $post->update(['moderation_status' => 'approved']);

        return back()->with('status', 'Сообщение опубликовано.');
    }

    public function reject(Post $post)
    {
        $post->update(['moderation_status' => 'rejected']);

        return back()->with('status', 'Сообщение отклонено.');
    }
}
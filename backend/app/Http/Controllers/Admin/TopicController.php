<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::withCount('posts')->with(['user', 'category']);

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($categoryId = $request->query('category')) {
            $query->where('category_id', $categoryId);
        }

        match ($request->query('filter')) {
            'pinned' => $query->where('is_pinned', true),
            'locked' => $query->where('is_locked', true),
            default => null,
        };

        $topics = $query->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        $categories = Category::orderBy('order')->get();

        $counts = [
            'all' => Topic::count(),
            'pinned' => Topic::where('is_pinned', true)->count(),
            'locked' => Topic::where('is_locked', true)->count(),
        ];

        return view('admin.topics.index', compact('topics', 'categories', 'counts'));
    }

    public function togglePin(Topic $topic)
    {
        $topic->update(['is_pinned' => ! $topic->is_pinned]);

        return back()->with('status', $topic->is_pinned ? 'Тема закреплена.' : 'Тема откреплена.');
    }

    public function toggleLock(Topic $topic)
    {
        $topic->update(['is_locked' => ! $topic->is_locked]);

        return back()->with('status', $topic->is_locked ? 'Тема закрыта.' : 'Тема открыта.');
    }

    public function destroy(Topic $topic)
    {
        $topic->posts()->delete();
        $topic->delete();

        return back()->with('status', 'Тема удалена.');
    }
}

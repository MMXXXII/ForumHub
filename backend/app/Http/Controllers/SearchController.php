<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q'));

        if ($query === '' || mb_strlen($query) < 2) {
            return view('search.index', [
                'query' => $query,
                'topics' => null,
                'users' => collect(),
                'tooShort' => $query !== '',
            ]);
        }

        $like = '%'.$query.'%';

        $matchedTopicIds = Post::where('body', 'like', $like)
            ->where('moderation_status', 'approved')
            ->pluck('topic_id')
            ->unique();

        $topics = Topic::withCount('posts')
            ->with(['user', 'category'])
            ->withMax('posts', 'created_at')
            ->where(fn ($q) => $q->where('title', 'like', $like)->orWhereIn('id', $matchedTopicIds))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $users = User::withCount('posts')
            ->where('name', 'like', $like)
            ->orderByDesc('posts_count')
            ->take(10)
            ->get();

        return view('search.index', compact('query', 'topics', 'users') + ['tooShort' => false]);
    }
}
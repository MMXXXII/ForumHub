<?php

namespace App\Http\Controllers;

use App\Models\Topic;

class HomeController extends Controller
{
    public function index()
    {
        $relations = ['user', 'category', 'posts' => fn ($q) => $q->latest()->limit(1)->with('user')];

        $pinnedTopics = Topic::withCount('posts')
            ->with($relations)
            ->withMax('posts', 'created_at')
            ->where('is_pinned', true)
            ->latest()
            ->get();

        $topics = Topic::withCount('posts')
            ->with($relations)
            ->withMax('posts', 'created_at')
            ->where('is_pinned', false)
            ->latest()
            ->paginate(15);

        return view('home', compact('pinnedTopics', 'topics'));
    }
}

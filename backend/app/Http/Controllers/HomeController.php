<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $topics = Topic::withCount('posts')
            ->with(['user', 'category'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'users' => User::count(),
            'topics' => Topic::count(),
            'posts' => Post::count(),
        ];

        return view('home', compact('topics', 'stats'));
    }
}
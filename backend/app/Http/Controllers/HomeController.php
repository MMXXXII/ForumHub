<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $topics = Topic::withCount('posts')
            ->with(['user', 'category', 'posts' => fn ($q) => $q->latest()->limit(1)->with('user')])
            ->withMax('posts', 'created_at')
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('home', compact('topics'));
    }
}
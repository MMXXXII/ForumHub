<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'topics' => Topic::count(),
            'posts' => Post::count(),
            'hidden_posts' => Post::where('is_hidden', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
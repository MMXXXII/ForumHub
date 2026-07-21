<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->loadCount(['posts', 'topics']);

        $topics = $user->topics()
            ->withCount('posts')
            ->with('category')
            ->latest()
            ->take(3)
            ->get();

        $wallPosts = $user->wallPosts()
            ->whereNull('parent_id')
            ->with(['author', 'replies.author'])
            ->orderByDesc('is_pinned')
            ->latest()
            ->paginate(20);

        return view('profile.show', compact('user', 'topics', 'wallPosts'));
    }
}

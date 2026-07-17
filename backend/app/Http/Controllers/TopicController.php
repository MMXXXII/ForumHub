<?php

namespace App\Http\Controllers;

use App\Models\Topic;

class TopicController extends Controller
{
    public function show(Topic $topic)
    {
        $query = $topic->posts()->with('user');

        if (! auth()->check() || ! auth()->user()->isModerator()) {
            $query->where('is_hidden', false);
        }

        $posts = $query->orderBy('created_at')->paginate(20);

        return view('topics.show', compact('topic', 'posts'));
    }
}
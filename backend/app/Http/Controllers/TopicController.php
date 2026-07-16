<?php

namespace App\Http\Controllers;

use App\Models\Topic;

class TopicController extends Controller
{
    public function show(Topic $topic)
    {
        $posts = $topic->posts()
            ->where('is_hidden', false)
            ->with('user')
            ->orderBy('created_at')
            ->paginate(20);

        return view('topics.show', compact('topic', 'posts'));
    }
}
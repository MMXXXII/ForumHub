<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Topic $topic)
    {
        if ($topic->is_locked) {
            return back()->withErrors(['body' => 'Тема закрыта для ответов.']);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
            'parent_id' => ['nullable', 'integer', 'exists:posts,id'],
        ]);

        if (! empty($validated['parent_id'])) {
            $ok = Post::where('id', $validated['parent_id'])->where('topic_id', $topic->id)->exists();
            if (! $ok) {
                return back()->withErrors(['body' => 'Некорректный ответ.']);
            }
        }

        $topic->posts()->create([
            'user_id'   => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'body'      => $validated['body'],
        ]);

        return back()->with('status', 'Сообщение добавлено.');
    }

    public function destroy(Request $request, Post $post)
    {
        $user = $request->user();

        if ($user->id !== $post->user_id && ! $user->isModerator()) {
            abort(403);
        }

        $post->delete();

        return back()->with('status', 'Сообщение удалено.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use App\Services\ModerationService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Topic $topic, ModerationService $moderation)
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

        $verdict = $moderation->check($validated['body']);

        $post = $topic->posts()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
            'moderation_status' => $verdict['status'],
            'confidence_score' => $verdict['score'],
        ]);

        if (! empty($validated['parent_id']) && $verdict['status'] === 'approved') {
            $parent = Post::find($validated['parent_id']);
            if ($parent && $parent->user_id !== $request->user()->id) {
                \App\Models\Notification::create([
                    'user_id' => $parent->user_id,
                    'actor_id' => $request->user()->id,
                    'type' => 'reply',
                    'url' => route('topics.show', $topic).'#post-'.$post->id,
                    'preview' => \Illuminate\Support\Str::limit($validated['body'], 80),
                ]);
            }
        }

        if ($verdict['status'] === 'rejected') {
            return back()->with('warning', 'Сообщение отправлено на проверку модератору: система обнаружила возможное нарушение правил.');
        }

        if ($verdict['status'] === 'pending') {
            return back()->with('warning', 'Сообщение отправлено на проверку модератору.');
        }

        return back()->with('status', 'Сообщение добавлено.');
    }

    public function update(Request $request, Post $post, ModerationService $moderation)
    {
        if ($request->user()->id !== $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $verdict = $moderation->check($validated['body']);

        $post->update([
            'body' => $validated['body'],
            'edited_at' => now(),
            'moderation_status' => $verdict['status'],
            'confidence_score' => $verdict['score'],
        ]);

        if ($verdict['status'] === 'rejected') {
            return redirect()->route('topics.show', $post->topic)
                ->with('warning', 'Изменённое сообщение отправлено на проверку модератору: система обнаружила возможное нарушение правил.');
        }

        return redirect()->route('topics.show', $post->topic)->with('status', 'Сообщение изменено.');
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

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WallPost;
use Illuminate\Http\Request;

class WallPostController extends Controller
{
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:wall_posts,id'],
        ]);

        if (! empty($validated['parent_id'])) {
            $ok = WallPost::where('id', $validated['parent_id'])
                ->where('profile_user_id', $user->id)
                ->whereNull('parent_id')
                ->exists();

            if (! $ok) {
                abort(403);
            }
        }

        WallPost::create([
            'profile_user_id' => $user->id,
            'author_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
        ]);
        
        if ($request->user()->id !== $user->id) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'actor_id' => $request->user()->id,
                'type' => 'wall',
                'url' => route('profile.show', $user),
                'preview' => \Illuminate\Support\Str::limit($validated['body'], 80),
            ]);
        }

        return back()->with('status', 'Сообщение оставлено.');
    }

    public function togglePin(Request $request, WallPost $wallPost)
    {
        if ($request->user()->id !== $wallPost->profile_user_id) {
            abort(403);
        }

        $wallPost->update(['is_pinned' => ! $wallPost->is_pinned]);

        return back();
    }

    public function destroy(Request $request, WallPost $wallPost)
    {
        $user = $request->user();

        $allowed = $user->id === $wallPost->author_id
            || $user->id === $wallPost->profile_user_id
            || $user->isModerator();

        if (! $allowed) {
            abort(403);
        }

        $wallPost->delete();

        return back()->with('status', 'Сообщение удалено.');
    }
}

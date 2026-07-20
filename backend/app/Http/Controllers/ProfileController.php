<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'status' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        $data = ['status' => $validated['status'] ?? null];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show', $user)->with('status', 'Профиль обновлён.');
    }

    public function card(User $user)
    {
        $user->loadCount(['posts', 'topics']);

        return response()->json([
            'name' => $user->name,
            'url' => route('profile.show', $user),
            'avatar' => $user->avatarUrl(),
            'initial' => mb_strtoupper(mb_substr($user->name, 0, 1)),
            'role' => $user->role,
            'color' => $user->roleColor(),
            'status' => $user->status,
            'topics' => $user->topics_count,
            'posts' => $user->posts_count,
            'joined' => $user->created_at->format('d.m.Y'),
            'banned' => $user->isBanned(),
        ]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['posts', 'topics']);

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        }

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        match ($request->query('sort')) {
            'posts' => $query->orderByDesc('posts_count'),
            'name' => $query->orderBy('name'),
            default => $query->orderByDesc('created_at'),
        };

        $users = $query->paginate(25)->withQueryString();

        $counts = [
            'total' => User::count(),
            'banned' => User::whereNotNull('banned_until')->where('banned_until', '>', now())->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'in:user,moderator,admin'],
        ]);

        if ($user->id === $request->user()->id && $validated['role'] !== $user->role) {
            return back()->withErrors(['role' => 'Нельзя изменить собственную роль.']);
        }

        $user->forceFill([
            'name' => $validated['name'],
            'status' => $validated['status'] ?? null,
            'role' => $validated['role'],
        ])->save();

        return back()->with('status', 'Данные пользователя обновлены.');
    }

    public function ban(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['ban' => 'Нельзя заблокировать самого себя.']);
        }

        $validated = $request->validate([
            'duration' => ['required', 'in:1,7,30,permanent'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $until = $validated['duration'] === 'permanent'
            ? now()->addYears(100)
            : now()->addDays((int) $validated['duration']);

        $user->forceFill([
            'banned_until' => $until,
            'ban_reason' => $validated['reason'] ?? null,
        ])->save();

        return back()->with('status', 'Пользователь заблокирован.');
    }

    public function unban(User $user)
    {
        $user->forceFill(['banned_until' => null, 'ban_reason' => null])->save();

        return back()->with('status', 'Блокировка снята.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['delete' => 'Нельзя удалить собственный аккаунт.']);
        }

        $user->delete();

        return back()->with('status', 'Пользователь удалён.');
    }
}

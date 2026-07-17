<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(30);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:user,moderator,admin'],
        ]);

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['role' => 'Нельзя изменить собственную роль.']);
        }

        $user->forceFill(['role' => $validated['role']])->save();

        return back()->with('status', 'Роль обновлена.');
    }
}
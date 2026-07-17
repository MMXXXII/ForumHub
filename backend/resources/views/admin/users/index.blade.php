@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-semibold text-black mb-6">Пользователи</h1>

<div class="border border-neutral-200 rounded-lg overflow-hidden">
    @foreach ($users as $user)
        <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 last:border-b-0">
            <div>
                <div class="text-sm font-medium text-black">{{ $user->name }}</div>
                <div class="text-xs text-neutral-500">{{ $user->email }}</div>
            </div>

            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="role" class="border border-neutral-200 rounded text-sm px-2 py-1" @if($user->id === auth()->id()) disabled @endif>
                    @foreach (['user', 'moderator', 'admin'] as $role)
                        <option value="{{ $role }}" @selected($user->role === $role)>{{ $role }}</option>
                    @endforeach
                </select>
                @if ($user->id !== auth()->id())
                    <button type="submit" class="text-xs text-neutral-500 hover:text-black border border-neutral-200 rounded px-2 py-1">Сохранить</button>
                @endif
            </form>
        </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
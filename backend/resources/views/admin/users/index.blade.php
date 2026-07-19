@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold text-black">Пользователи</h1>
    <div class="text-xs text-neutral-500">
        всего: <span class="text-black font-medium">{{ $counts['total'] }}</span>
        <span class="text-neutral-300 mx-1">·</span>
        заблокировано: <span class="text-black font-medium">{{ $counts['banned'] }}</span>
    </div>
</div>

<form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2 mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Поиск по имени, почте или ID" class="border border-neutral-200 rounded px-3 py-1.5 text-sm flex-1 focus:outline-none focus:border-black">
    <select name="role" class="border border-neutral-200 rounded px-2 py-1.5 text-sm focus:outline-none focus:border-black">
        <option value="">Все роли</option>
        <option value="user" @selected(request('role') === 'user')>user</option>
        <option value="moderator" @selected(request('role') === 'moderator')>moderator</option>
        <option value="admin" @selected(request('role') === 'admin')>admin</option>
    </select>
    <select name="sort" class="border border-neutral-200 rounded px-2 py-1.5 text-sm focus:outline-none focus:border-black">
        <option value="">Новые</option>
        <option value="name" @selected(request('sort') === 'name')>По имени</option>
        <option value="posts" @selected(request('sort') === 'posts')>По активности</option>
    </select>
    <button type="submit" class="text-xs bg-black text-white rounded px-3 py-2 hover:bg-neutral-800">Найти</button>
    @if (request('q') || request('role') || request('sort'))
        <a href="{{ route('admin.users.index') }}" class="text-xs text-neutral-500 hover:text-black px-2">Сбросить</a>
    @endif
</form>

<div class="border border-neutral-200 rounded-lg overflow-hidden bg-white">
    <div class="flex items-center gap-2 px-4 py-2 bg-neutral-50 border-b border-neutral-200 text-[11px] font-semibold uppercase tracking-wider text-neutral-400">
        <span class="w-10"></span>
        <span class="flex-1">Имя</span>
        <span class="flex-1">Статус</span>
        <span class="w-28 text-center">Роль</span>
        <span class="w-24"></span>
        <span class="w-8"></span>
    </div>

    @forelse ($users as $user)
        <div class="px-4 py-3 border-b border-neutral-200 last:border-b-0 {{ $user->isBanned() ? 'bg-red-50/40' : '' }}">
            <div class="flex items-center gap-2">
                <a href="{{ route('profile.show', $user) }}" class="w-10 shrink-0">
                    <x-avatar :user="$user" class="w-8 h-8 text-xs" />
                </a>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex items-center gap-2 flex-1 min-w-0">
                    @csrf
                    @method('PATCH')
                    <input type="text" name="name" value="{{ $user->name }}" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1 min-w-0 focus:outline-none focus:border-black">
                    <input type="text" name="status" value="{{ $user->status }}" placeholder="—" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1 min-w-0 focus:outline-none focus:border-black">
                    <select name="role" class="border border-neutral-200 rounded px-2 py-1 text-sm w-28 focus:outline-none focus:border-black" @disabled($user->id === auth()->id())>
                        @foreach (['user', 'moderator', 'admin'] as $role)
                            <option value="{{ $role }}" @selected($user->role === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-24 text-xs border border-neutral-200 rounded px-2 py-1 hover:bg-neutral-50 shrink-0">Сохранить</button>
                </form>

                @if ($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="w-8 shrink-0 flex justify-center" onsubmit="return confirm('Удалить пользователя «{{ $user->name }}» со всеми темами и сообщениями?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-neutral-400 hover:text-red-600 transition" title="Удалить пользователя">
                            <i class="ti ti-trash text-base"></i>
                        </button>
                    </form>
                @else
                    <span class="w-8 shrink-0"></span>
                @endif
            </div>

            <div class="flex items-center gap-3 mt-2 pl-12 text-xs text-neutral-400 flex-wrap">
                <span>ID {{ $user->id }}</span>
                <span class="text-neutral-300">·</span>
                <span>{{ $user->email }}</span>
                <span class="text-neutral-300">·</span>
                <span>рег. {{ $user->created_at->timezone('Asia/Irkutsk')->format('d.m.Y') }}</span>
                <span class="text-neutral-300">·</span>
                <span>{{ $user->topics_count }} тем</span>
                <span class="text-neutral-300">·</span>
                <span>{{ $user->posts_count }} сообщений</span>

                @if ($user->isBanned())
                    <span class="text-red-600 font-medium">
                        заблокирован {{ $user->isPermanentlyBanned() ? 'навсегда' : 'до '.$user->banned_until->timezone('Asia/Irkutsk')->format('d.m.Y H:i') }}
                        @if ($user->ban_reason) — {{ $user->ban_reason }} @endif
                    </span>
                    <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-xs text-green-700 hover:underline">Разблокировать</button>
                    </form>
                @elseif ($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.ban', $user) }}" class="flex items-center gap-1.5">
                        @csrf
                        @method('PATCH')
                        <input type="text" name="reason" placeholder="Причина" class="border border-neutral-200 rounded px-2 py-0.5 text-xs w-40 focus:outline-none focus:border-black">
                        <select name="duration" class="border border-neutral-200 rounded px-1.5 py-0.5 text-xs focus:outline-none focus:border-black">
                            <option value="1">1 день</option>
                            <option value="7">7 дней</option>
                            <option value="30">30 дней</option>
                            <option value="permanent">навсегда</option>
                        </select>
                        <button type="submit" class="text-xs text-red-600 hover:underline">Заблокировать</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="px-4 py-10 text-center text-sm text-neutral-400">Никого не найдено</div>
    @endforelse
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection
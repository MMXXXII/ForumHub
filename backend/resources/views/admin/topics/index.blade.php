@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold text-black">Темы</h1>
    <div class="text-xs text-neutral-500">
        всего: <span class="text-black font-medium">{{ $counts['all'] }}</span>
        <span class="text-neutral-300 mx-1">·</span>
        закреплено: <span class="text-black font-medium">{{ $counts['pinned'] }}</span>
        <span class="text-neutral-300 mx-1">·</span>
        закрыто: <span class="text-black font-medium">{{ $counts['locked'] }}</span>
    </div>
</div>

<form method="GET" action="{{ route('admin.topics.index') }}" class="flex items-center gap-2 mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Поиск по названию или автору" class="border border-neutral-200 rounded px-3 py-1.5 text-sm flex-1 focus:outline-none focus:border-black">
    <select name="category" class="border border-neutral-200 rounded px-2 py-1.5 text-sm focus:outline-none focus:border-black">
        <option value="">Все разделы</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    <select name="filter" class="border border-neutral-200 rounded px-2 py-1.5 text-sm focus:outline-none focus:border-black">
        <option value="">Все темы</option>
        <option value="pinned" @selected(request('filter') === 'pinned')>Закреплённые</option>
        <option value="locked" @selected(request('filter') === 'locked')>Закрытые</option>
    </select>
    <button type="submit" class="text-xs bg-black text-white rounded px-3 py-2 hover:bg-neutral-800">Найти</button>
    @if (request('q') || request('category') || request('filter'))
        <a href="{{ route('admin.topics.index') }}" class="text-xs text-neutral-500 hover:text-black px-2">Сбросить</a>
    @endif
</form>

<div class="border border-neutral-200 rounded-lg overflow-hidden bg-white">
    <div class="flex items-center gap-2 px-4 py-2 bg-neutral-50 border-b border-neutral-200 text-[11px] font-semibold uppercase tracking-wider text-neutral-400">
        <span class="flex-1">Тема</span>
        <span class="w-20 text-center">Ответов</span>
        <span class="w-24 text-center">Создана</span>
        <span class="w-28 text-center">Действия</span>
    </div>

    @forelse ($topics as $topic)
        <div class="flex items-center gap-2 px-4 py-2.5 border-b border-neutral-200 last:border-b-0 hover:bg-neutral-50 transition">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                    @if ($topic->is_pinned)
                        <i class="ti ti-pin-filled text-sm text-neutral-400 shrink-0" title="Закреплена"></i>
                    @endif
                    @if ($topic->is_locked)
                        <i class="ti ti-lock text-sm text-neutral-400 shrink-0" title="Закрыта"></i>
                    @endif
                    <a href="{{ route('topics.show', $topic) }}" class="text-sm font-medium text-black hover:underline truncate">{{ $topic->title }}</a>
                </div>
                <div class="text-xs text-neutral-400 mt-0.5 truncate">
                    {{ $topic->category->name }}
                    <span class="text-neutral-300 mx-1">·</span>
                    {{ $topic->user->name }}
                </div>
            </div>

            <div class="w-20 text-center text-sm text-neutral-600 shrink-0">{{ $topic->posts_count }}</div>

            <div class="w-24 text-center text-xs text-neutral-400 shrink-0">
                <x-date :value="$topic->created_at" format="d.m.Y" />
            </div>

            <div class="w-28 flex items-center justify-center gap-1 shrink-0">
                <form method="POST" action="{{ route('admin.topics.pin', $topic) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-neutral-400 hover:text-black p-1.5 rounded hover:bg-neutral-100 transition" title="{{ $topic->is_pinned ? 'Открепить' : 'Закрепить' }}">
                        <i class="ti {{ $topic->is_pinned ? 'ti-pinned-off' : 'ti-pin' }} text-base"></i>
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.topics.lock', $topic) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-neutral-400 hover:text-black p-1.5 rounded hover:bg-neutral-100 transition" title="{{ $topic->is_locked ? 'Открыть' : 'Закрыть' }}">
                        <i class="ti {{ $topic->is_locked ? 'ti-lock-open' : 'ti-lock' }} text-base"></i>
                    </button>
                </form>

                @if (auth()->user()->isAdmin())
                    <button type="button" class="text-neutral-400 hover:text-red-600 p-1.5 rounded hover:bg-neutral-100 transition" title="Удалить тему"
                        onclick="openDeleteModal('{{ route('admin.topics.destroy', $topic) }}', 'Удалить тему?', 'Тема «{{ $topic->title }}» и все её сообщения будут удалены безвозвратно.')">
                        <i class="ti ti-trash text-base"></i>
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="px-4 py-10 text-center text-sm text-neutral-400">Ничего не найдено</div>
    @endforelse
</div>

<div class="mt-4">{{ $topics->links() }}</div>
@endsection
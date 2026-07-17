@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('home') }}" class="text-xs text-neutral-400 hover:text-black">&larr; Все разделы</a>
    <h1 class="text-xl font-semibold text-black mt-1">{{ $category->name }}</h1>
    <p class="text-sm text-neutral-500 mt-1">{{ $category->description }}</p>
</div>

<div class="border border-neutral-200 rounded-lg overflow-hidden shadow-sm">
    @forelse ($topics as $topic)
        <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 last:border-b-0 hover:bg-neutral-50 transition">
            <a href="{{ route('topics.show', $topic) }}" class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    @if ($topic->is_pinned)
                        <span class="text-[10px] uppercase tracking-wide bg-black text-white px-1.5 py-0.5 rounded">закреплено</span>
                    @endif
                    @if ($topic->is_locked)
                        <span class="text-[10px] uppercase tracking-wide bg-neutral-200 text-neutral-600 px-1.5 py-0.5 rounded">закрыто</span>
                    @endif
                    <div class="text-black text-sm font-medium truncate">{{ $topic->title }}</div>
                </div>
                <div class="text-neutral-400 text-xs mt-0.5">{{ $topic->user->name }}</div>
            </a>

            <div class="flex items-center gap-3 ml-4">
                @auth
                    @if (auth()->user()->isModerator())
                        <form method="POST" action="{{ route('admin.topics.pin', $topic) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs text-neutral-400 hover:text-black">{{ $topic->is_pinned ? 'Открепить' : 'Закрепить' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.topics.lock', $topic) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs text-neutral-400 hover:text-black">{{ $topic->is_locked ? 'Открыть' : 'Закрыть' }}</button>
                        </form>
                    @endif
                @endauth
                <div class="text-neutral-400 text-xs whitespace-nowrap">{{ $topic->posts_count }} ответов</div>
            </div>
        </div>
    @empty
        <div class="px-4 py-8 text-center text-neutral-400 text-sm">В этом разделе пока нет тем</div>
    @endforelse
</div>

<div class="mt-4">
    {{ $topics->links() }}
</div>
@endsection
@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-semibold text-black mb-6">Темы</h1>

<div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
    @foreach ($topics as $topic)
        <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 last:border-b-0">
            <div class="min-w-0">
                <a href="{{ route('topics.show', $topic) }}" class="text-sm font-medium text-black hover:underline">{{ $topic->title }}</a>
                <div class="text-xs text-neutral-400 mt-0.5">{{ $topic->category->name }} &middot; {{ $topic->user->name }} &middot; {{ $topic->posts_count }} ответов</div>
            </div>

            <div class="flex items-center gap-2 ml-4 shrink-0">
                @if ($topic->is_pinned)
                    <span class="text-[10px] uppercase bg-black text-white px-1.5 py-0.5 rounded">закреплено</span>
                @endif
                @if ($topic->is_locked)
                    <span class="text-[10px] uppercase bg-neutral-200 text-neutral-600 px-1.5 py-0.5 rounded">закрыто</span>
                @endif

                <form method="POST" action="{{ route('admin.topics.pin', $topic) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs text-neutral-500 hover:text-black">{{ $topic->is_pinned ? 'Открепить' : 'Закрепить' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.topics.lock', $topic) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs text-neutral-500 hover:text-black">{{ $topic->is_locked ? 'Открыть' : 'Закрыть' }}</button>
                </form>
                @if (auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('admin.topics.destroy', $topic) }}" onsubmit="return confirm('Удалить тему целиком?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:underline">Удалить</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div class="mt-4">{{ $topics->links() }}</div>
@endsection
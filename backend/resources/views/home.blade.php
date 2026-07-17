@extends('layouts.app')

@section('content')
<div class="flex items-center gap-6 mb-6 text-sm text-neutral-500 border-b border-neutral-200 pb-4">
    <div><span class="text-black font-semibold">{{ $stats['users'] }}</span> участников</div>
    <div><span class="text-black font-semibold">{{ $stats['topics'] }}</span> тем</div>
    <div><span class="text-black font-semibold">{{ $stats['posts'] }}</span> сообщений</div>
</div>

<div class="border border-neutral-200 rounded-lg overflow-hidden">
    <div class="hidden sm:flex items-center gap-3 px-4 py-2 bg-neutral-50 border-b border-neutral-200 text-xs text-neutral-400">
        <span class="flex-1">Тема</span>
        <span class="w-16 text-center">Ответов</span>
        <span class="w-28 text-right">Активность</span>
    </div>

    @forelse ($topics as $topic)
        <a href="{{ route('topics.show', $topic) }}" class="flex items-center gap-3 px-4 py-3 border-b border-neutral-200 last:border-b-0 hover:bg-neutral-50 transition">
            <div class="w-8 h-8 rounded-full bg-neutral-100 text-neutral-500 flex items-center justify-center text-xs font-medium shrink-0">
                {{ mb_strtoupper(mb_substr($topic->user->name, 0, 1)) }}
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                    @if ($topic->is_pinned)
                        <span class="text-neutral-400 shrink-0" title="Закреплено">&#9733;</span>
                    @endif
                    @if ($topic->is_locked)
                        <span class="text-neutral-400 shrink-0" title="Закрыто">&#128274;</span>
                    @endif
                    <span class="text-sm font-medium text-black truncate">{{ $topic->title }}</span>
                </div>
                <div class="text-xs text-neutral-400 mt-0.5 truncate">
                    {{ $topic->user->name }} <span class="text-neutral-300">&middot;</span> {{ $topic->category->name }}
                </div>
            </div>

            <div class="w-16 text-center text-sm text-neutral-600 shrink-0">{{ $topic->posts_count }}</div>

            <div class="w-28 text-right text-xs text-neutral-400 shrink-0">
                {{ \Illuminate\Support\Carbon::parse($topic->posts_max_created_at ?? $topic->created_at)->timezone('Asia/Irkutsk')->format('d.m.Y H:i') }}
            </div>
        </a>
    @empty
        <div class="px-4 py-8 text-center text-neutral-400 text-sm">Пока нет тем</div>
    @endforelse
</div>

<div class="mt-4">
    {{ $topics->links() }}
</div>
@endsection
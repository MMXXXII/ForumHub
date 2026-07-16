@extends('layouts.app')

@section('content')
<div class="flex items-center gap-6 mb-6 text-sm text-neutral-500 border-b border-neutral-200 pb-4">
    <div><span class="text-black font-semibold">{{ $stats['users'] }}</span> участников</div>
    <div><span class="text-black font-semibold">{{ $stats['topics'] }}</span> тем</div>
    <div><span class="text-black font-semibold">{{ $stats['posts'] }}</span> сообщений</div>
</div>

<div class="space-y-3">
    @foreach ($topics as $topic)
        <a href="{{ route('topics.show', $topic) }}" class="block border border-neutral-200 rounded-lg p-4 hover:border-neutral-300 hover:shadow-sm transition">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="text-[11px] uppercase tracking-wide bg-neutral-100 text-neutral-500 px-1.5 py-0.5 rounded">{{ $topic->category->name }}</span>
                @if ($topic->is_pinned)
                    <span class="text-[11px] uppercase tracking-wide bg-black text-white px-1.5 py-0.5 rounded">закреплено</span>
                @endif
            </div>
            <div class="text-black text-sm font-medium">{{ $topic->title }}</div>
            <div class="flex items-center gap-3 mt-2 text-xs text-neutral-400">
                <span>{{ $topic->user->name }}</span>
                <span>&middot;</span>
                <span>{{ $topic->created_at->format('d.m.Y H:i') }}</span>
                <span>&middot;</span>
                <span>{{ $topic->posts_count }} ответов</span>
            </div>
        </a>
    @endforeach
</div>

<div class="mt-4">
    {{ $topics->links() }}
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('categories.show', $topic->category) }}" class="text-xs text-neutral-400 hover:text-black">&larr; {{ $topic->category->name }}</a>
    <h1 class="text-xl font-semibold text-black mt-1">{{ $topic->title }}</h1>
</div>

<div class="space-y-3">
    @foreach ($posts as $post)
        <div class="border border-neutral-200 rounded-lg p-4 shadow-sm {{ $post->is_hidden ? 'opacity-50' : '' }}">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="text-sm font-medium text-black">{{ $post->user->name }}</div>
                    @if ($post->is_hidden)
                        <span class="text-[10px] uppercase tracking-wide bg-neutral-200 text-neutral-600 px-1.5 py-0.5 rounded">скрыто</span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-xs text-neutral-400">{{ $post->created_at->format('d.m.Y H:i') }}</div>
                    @auth
                        @if (auth()->user()->isModerator())
                            <form method="POST" action="{{ route('admin.posts.hide', $post) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs text-neutral-400 hover:text-black">{{ $post->is_hidden ? 'Показать' : 'Скрыть' }}</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="text-sm text-neutral-800 whitespace-pre-line">{{ $post->body }}</div>
        </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $posts->links() }}
</div>
@endsection
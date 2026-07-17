@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-semibold text-black mb-6">Сообщения</h1>

<div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
    @foreach ($posts as $post)
        <div class="flex items-start justify-between px-4 py-3 border-b border-neutral-200 last:border-b-0 gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-black">{{ $post->user->name }}</span>
                    <span class="text-xs text-neutral-400">в</span>
                    <a href="{{ route('topics.show', $post->topic) }}" class="text-xs text-neutral-500 hover:underline truncate">{{ $post->topic->title }}</a>
                    @if ($post->is_hidden)
                        <span class="text-[10px] uppercase bg-neutral-200 text-neutral-600 px-1.5 py-0.5 rounded">скрыто</span>
                    @endif
                </div>
                <div class="text-sm text-neutral-700 mt-1 line-clamp-2">{{ $post->body }}</div>
                <div class="text-xs text-neutral-400 mt-1">{{ $post->created_at->format('d.m.Y H:i') }}</div>
            </div>

            <form method="POST" action="{{ route('admin.posts.hide', $post) }}" class="shrink-0">
                @csrf @method('PATCH')
                <button type="submit" class="text-xs text-neutral-500 hover:text-black border border-neutral-200 rounded px-2 py-1">{{ $post->is_hidden ? 'Показать' : 'Скрыть' }}</button>
            </form>
        </div>
    @endforeach
</div>

<div class="mt-4">{{ $posts->links() }}</div>
@endsection
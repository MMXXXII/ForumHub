@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold text-black">Сообщения</h1>
    <div class="text-xs text-neutral-500">всего: <span class="text-black font-medium">{{ $posts->total() }}</span></div>
</div>

<form method="GET" action="{{ route('admin.posts.index') }}" class="flex items-center gap-2 mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Поиск по тексту или автору" class="border border-neutral-200 rounded px-3 py-1.5 text-sm flex-1 focus:outline-none focus:border-black">
    <button type="submit" class="text-xs bg-black text-white rounded px-3 py-2 hover:bg-neutral-800">Найти</button>
    @if (request('q'))
        <a href="{{ route('admin.posts.index') }}" class="text-xs text-neutral-500 hover:text-black px-2">Сбросить</a>
    @endif
</form>

<div class="border border-neutral-200 rounded-lg overflow-hidden bg-white">
    @forelse ($posts as $post)
        <div class="flex items-start gap-3 px-4 py-3 border-b border-neutral-200 last:border-b-0">
            <x-avatar :user="$post->user" class="w-8 h-8 text-xs" />

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <x-username :user="$post->user" class="text-sm" />
                    <span class="text-xs text-neutral-400">в</span>
                    <a href="{{ route('topics.show', $post->topic) }}#post-{{ $post->id }}" class="text-xs text-neutral-500 hover:text-black hover:underline truncate">{{ $post->topic->title }}</a>
                </div>
                <div class="text-sm text-neutral-700 mt-1 line-clamp-2">{{ $post->body }}</div>
                <div class="text-xs text-neutral-400 mt-1">
                    ID {{ $post->id }}
                    <span class="text-neutral-300 mx-1">·</span>
                    {{ $post->created_at->timezone('Asia/Irkutsk')->format('d.m.Y H:i') }}
                    @if ($post->edited_at)
                        <span class="text-neutral-300 mx-1">·</span>изменено
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="shrink-0" onsubmit="return confirm('Удалить сообщение? Ответы на него останутся.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-neutral-400 hover:text-red-600 transition" title="Удалить сообщение">
                    <i class="ti ti-trash text-base"></i>
                </button>
            </form>
        </div>
    @empty
        <div class="px-4 py-10 text-center text-sm text-neutral-400">Ничего не найдено</div>
    @endforelse
</div>

<div class="mt-4">{{ $posts->links() }}</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('categories.show', $topic->category) }}" class="text-xs text-neutral-400 hover:text-black">&larr; {{ $topic->category->name }}</a>
    <h1 class="text-xl font-semibold text-black mt-1">{{ $topic->title }}</h1>
</div>

<div class="space-y-3">
    @foreach ($posts as $post)
        <div class="border border-neutral-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm font-medium text-black">{{ $post->user->name }}</div>
                <div class="text-xs text-neutral-400">{{ $post->created_at->format('d.m.Y H:i') }}</div>
            </div>
            <div class="text-sm text-neutral-800 whitespace-pre-line">{{ $post->body }}</div>
        </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $posts->links() }}
</div>
@endsection
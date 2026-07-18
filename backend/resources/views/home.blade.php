@extends('layouts.app')

@section('content')
<h1 class="text-lg font-semibold text-black mb-4">Все обсуждения</h1>

<div class="border border-neutral-200 rounded-xl overflow-hidden bg-white">
    @forelse ($topics as $topic)
        <a href="{{ route('topics.show', $topic) }}" class="flex items-center gap-3 px-4 py-3.5 border-b border-neutral-200 last:border-b-0 hover:bg-neutral-50 transition group">
            <x-avatar :user="$topic->user" class="w-10 h-10 text-sm" />

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                    @if ($topic->is_pinned)
                        <i class="ti ti-pin-filled text-sm text-neutral-400 shrink-0"></i>
                    @endif
                    @if ($topic->is_locked)
                        <i class="ti ti-lock text-sm text-neutral-400 shrink-0"></i>
                    @endif
                    <span class="text-[15px] font-semibold text-black truncate group-hover:underline">{{ $topic->title }}</span>
                </div>
                <div class="flex items-center gap-2 mt-1 min-w-0">
                    <span class="text-[10px] font-semibold uppercase tracking-wide bg-neutral-100 text-neutral-500 px-1.5 py-0.5 rounded shrink-0">{{ $topic->category->name }}</span>
                    <x-username :user="$topic->user" class="text-xs" :link="false" />
                    @if ($topic->posts->isNotEmpty())
                        <span class="text-xs text-neutral-300">·</span>
                        <span class="text-xs text-neutral-400 truncate">последний: {{ $topic->posts->first()->user->name }}</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-1.5 text-neutral-400 shrink-0">
                <i class="ti ti-message-circle text-base"></i>
                <span class="text-sm">{{ $topic->posts_count }}</span>
            </div>

            <div class="w-24 text-right text-xs text-neutral-400 shrink-0 hidden sm:block">
                {{ \Illuminate\Support\Carbon::parse($topic->posts_max_created_at ?? $topic->created_at)->timezone('Asia/Irkutsk')->diffForHumans() }}
            </div>
        </a>
    @empty
        <div class="px-4 py-12 text-center">
            <i class="ti ti-message-off text-3xl text-neutral-300"></i>
            <div class="text-sm text-neutral-400 mt-2">Пока нет тем</div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $topics->links() }}
</div>
@endsection
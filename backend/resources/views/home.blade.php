@extends('layouts.app')

@section('content')
@if ($pinnedTopics->isNotEmpty())
    <div class="border border-neutral-200 rounded-xl overflow-hidden bg-neutral-50/60 mb-4">
        <div class="flex items-center gap-1.5 px-4 py-2 border-b border-neutral-200 text-neutral-400">
            <i class="ti ti-pin-filled text-xs"></i>
            <span class="text-[10px] font-semibold uppercase tracking-wider">Закреплённые</span>
        </div>
        @foreach ($pinnedTopics as $topic)
            @include('topics.partials.row', ['topic' => $topic])
        @endforeach
    </div>
@endif

<div class="border border-neutral-200 rounded-xl overflow-hidden bg-white">
    @forelse ($topics as $topic)
        @include('topics.partials.row', ['topic' => $topic])
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
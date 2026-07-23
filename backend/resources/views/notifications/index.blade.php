@extends('layouts.app')

@section('content')
<h1 class="text-lg font-semibold text-black mb-4">Уведомления</h1>

<div class="border border-neutral-200 rounded-xl overflow-hidden bg-white">
    @forelse ($notifications as $note)
        <a href="{{ $note->url }}" class="flex gap-3 px-4 py-3 hover:bg-neutral-50 transition border-b border-neutral-200 last:border-b-0">
            <x-avatar :user="$note->actor" class="w-10 h-10 text-sm shrink-0" />
            <div class="min-w-0 flex-1">
                <div class="text-sm text-neutral-800">
                    <span class="font-medium">{{ $note->actor->name }}</span>
                    {{ $note->type === 'wall' ? 'написал на вашей стене' : 'ответил на ваше сообщение' }}
                </div>
                @if ($note->preview)
                    <div class="text-sm text-neutral-500 mt-0.5">{{ $note->preview }}</div>
                @endif
                <div class="text-xs text-neutral-400 mt-1"><x-date :value="$note->created_at" :human="true" /></div>
            </div>
        </a>
    @empty
        <div class="px-4 py-12 text-center">
            <i class="ti ti-bell-off text-3xl text-neutral-300"></i>
            <div class="text-sm text-neutral-400 mt-2">Уведомлений пока нет</div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $notifications->links() }}</div>
@endsection
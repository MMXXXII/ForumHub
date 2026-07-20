@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-lg font-semibold text-black">
        @if ($query === '')
            Поиск
        @else
            Результаты по запросу «{{ $query }}»
        @endif
    </h1>
    @if ($topics && $topics->total() > 0)
        <div class="text-xs text-neutral-400 mt-1">найдено тем: {{ $topics->total() }}</div>
    @endif
</div>

@if ($query === '')
    <div class="border border-neutral-200 rounded-xl py-12 text-center bg-white">
        <i class="ti ti-search text-3xl text-neutral-300"></i>
        <div class="text-sm text-neutral-400 mt-2">Введите запрос в поле поиска сверху</div>
    </div>
@elseif ($tooShort)
    <div class="border border-neutral-200 rounded-xl py-12 text-center bg-white">
        <i class="ti ti-alert-circle text-3xl text-neutral-300"></i>
        <div class="text-sm text-neutral-400 mt-2">Запрос слишком короткий — минимум 2 символа</div>
    </div>
@else
    @if ($users->isNotEmpty())
        <div class="mb-4">
            <div class="text-[11px] uppercase tracking-wider text-neutral-400 font-semibold mb-2">Участники</div>
            <div class="flex flex-wrap gap-2">
                @foreach ($users as $foundUser)
                    <a href="{{ route('profile.show', $foundUser) }}" class="flex items-center gap-2 border border-neutral-200 rounded-lg px-3 py-2 hover:bg-neutral-50 transition min-w-0">
                        <x-avatar :user="$foundUser" class="w-8 h-8 text-xs" />
                        <div class="min-w-0">
                            <x-username :user="$foundUser" class="text-sm block truncate" :link="false" />
                            <div class="text-[11px] text-neutral-400 leading-tight">{{ $foundUser->posts_count }} сообщений</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="border border-neutral-200 rounded-xl overflow-hidden bg-white">
        @forelse ($topics as $topic)
            @include('topics.partials.row', ['topic' => $topic])
        @empty
            <div class="px-4 py-12 text-center">
                <i class="ti ti-mood-empty text-3xl text-neutral-300"></i>
                <div class="text-sm text-neutral-400 mt-2">Ничего не найдено</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $topics->links() }}</div>
@endif
@endsection
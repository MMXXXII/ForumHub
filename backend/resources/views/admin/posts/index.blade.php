@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-semibold text-black mb-4">Сообщения</h1>

<div class="flex items-center gap-1 border-b border-neutral-200 mb-4">
    @php
        $tabs = [
            'all' => ['Все сообщения', $counts['all']],
            'moderation' => ['На модерации', $counts['moderation']],
        ];
    @endphp
    @foreach ($tabs as $key => [$label, $count])
        <a href="{{ route('admin.posts.index', ['tab' => $key]) }}" class="flex items-center gap-2 px-3 py-2 text-sm border-b-2 -mb-px transition {{ $tab === $key ? 'border-black text-black font-medium' : 'border-transparent text-neutral-500 hover:text-black' }}">
            {{ $label }}
            <span class="text-xs {{ $key === 'moderation' && $count > 0 ? 'bg-red-50 text-red-600 px-1.5 rounded' : 'text-neutral-400' }}">{{ $count }}</span>
        </a>
    @endforeach
</div>

<form method="GET" action="{{ route('admin.posts.index') }}" class="flex items-center gap-2 mb-4">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Поиск по тексту или автору" class="border border-neutral-200 rounded px-3 py-1.5 text-sm flex-1 focus:outline-none focus:border-black">
    <button type="submit" class="text-xs bg-black text-white rounded px-3 py-2 hover:bg-neutral-800">Найти</button>
    @if (request('q'))
        <a href="{{ route('admin.posts.index', ['tab' => $tab]) }}" class="text-xs text-neutral-500 hover:text-black px-2">Сбросить</a>
    @endif
</form>

<div class="space-y-3">
    @forelse ($posts as $post)
        @php $score = (float) $post->confidence_score; @endphp
        <div class="border border-neutral-200 rounded-lg p-4 bg-white">
            <div class="flex items-start justify-between gap-4 mb-2">
                <div class="flex items-center gap-2 flex-wrap min-w-0">
                    <x-username :user="$post->user" class="text-sm" />
                    <span class="text-xs text-neutral-400">в</span>
                    <a href="{{ route('topics.show', $post->topic) }}#post-{{ $post->id }}" class="text-xs text-neutral-500 hover:text-black hover:underline truncate">{{ $post->topic->title }}</a>
                    @if ($post->moderation_status === 'rejected')
                        <span class="text-[10px] uppercase tracking-wide bg-red-50 text-red-600 px-1.5 py-0.5 rounded">нарушение</span>
                    @elseif ($post->moderation_status === 'pending')
                        <span class="text-[10px] uppercase tracking-wide bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded">не проверено</span>
                    @endif
                </div>

                @if (! is_null($post->confidence_score))
                    <div class="text-right shrink-0">
                        <div class="text-sm font-semibold {{ $score >= 0.8 ? 'text-red-600' : ($score >= 0.5 ? 'text-amber-600' : 'text-neutral-500') }}">{{ number_format($score * 100, 1) }}%</div>
                        <div class="text-[10px] text-neutral-400 uppercase tracking-wide">токсичность</div>
                    </div>
                @endif
            </div>

            @if (! is_null($post->confidence_score))
                <div class="h-1 bg-neutral-100 rounded-full overflow-hidden mb-3">
                    <div class="h-full rounded-full {{ $score >= 0.8 ? 'bg-red-500' : ($score >= 0.5 ? 'bg-amber-500' : 'bg-neutral-400') }}" style="width: {{ max(1, min(100, $score * 100)) }}%"></div>
                </div>
            @endif

            <div class="text-sm text-neutral-800 whitespace-pre-line leading-relaxed {{ $post->moderation_status !== 'approved' ? 'bg-neutral-50 rounded-lg px-3 py-2' : '' }}">{{ $post->body }}</div>

            <div class="flex items-center justify-between mt-3">
                <div class="w-24 text-center text-xs text-neutral-400 shrink-0"><x-date :value="$topic->created_at" format="d.m.Y" /></div>
                    ID {{ $post->id }}
                    <span class="text-neutral-300 mx-1">&middot;</span>
                    <x-date :value="$post->created_at" />
                </div>

                <div class="flex items-center gap-2">
                    @if ($post->moderation_status !== 'approved')
                        <form method="POST" action="{{ route('admin.posts.approve', $post) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="flex items-center gap-1.5 text-xs text-green-700 border border-green-200 bg-green-50 rounded-lg px-3 py-1.5 hover:bg-green-100 transition">
                                <i class="ti ti-check text-sm"></i> Одобрить
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.posts.reject', $post) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="flex items-center gap-1.5 text-xs text-neutral-600 border border-neutral-200 rounded-lg px-3 py-1.5 hover:bg-neutral-50 transition">
                                <i class="ti ti-eye-off text-sm"></i> Скрыть
                            </button>
                        </form>
                    @endif

                    <button type="button" class="flex items-center gap-1.5 text-xs text-red-600 border border-red-200 rounded-lg px-3 py-1.5 hover:bg-red-50 transition"
                        onclick="openDeleteModal('{{ route('posts.destroy', $post) }}', 'Удалить сообщение?', 'Сообщение будет удалено безвозвратно.')">
                        <i class="ti ti-trash text-sm"></i> Удалить
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="border border-neutral-200 rounded-lg py-12 text-center bg-white">
            <i class="ti ti-checkbox text-3xl text-neutral-300"></i>
            <div class="text-sm text-neutral-400 mt-2">{{ $tab === 'moderation' ? 'Очередь пуста — всё проверено' : 'Ничего не найдено' }}</div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $posts->links() }}</div>
@endsection
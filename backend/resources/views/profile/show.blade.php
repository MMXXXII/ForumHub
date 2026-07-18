@extends('layouts.app')

@section('content')
<div class="border border-neutral-200 rounded-xl p-5 mb-4">
    <div class="flex items-start gap-4">
        <x-avatar :user="$user" class="w-20 h-20 text-2xl" />

        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-semibold {{ $user->roleColor() }}">{{ $user->name }}</h1>
            @if ($user->status)
                <div class="text-sm text-neutral-500 mt-0.5">{{ $user->status }}</div>
            @endif
            <div class="text-xs text-neutral-400 mt-2">
                На форуме с {{ $user->created_at->timezone('Asia/Irkutsk')->format('d.m.Y') }}
                <span class="text-neutral-300 mx-1">&middot;</span>
                {{ $user->topics_count }} тем
                <span class="text-neutral-300 mx-1">&middot;</span>
                {{ $user->posts_count }} сообщений
            </div>
        </div>

        @auth
            @if (auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}" class="text-xs text-neutral-500 hover:text-black border border-neutral-200 rounded px-3 py-1.5 shrink-0">Редактировать</a>
            @endif
        @endauth
    </div>
</div>

@if ($topics->isNotEmpty())
    <div class="border border-neutral-200 rounded-xl p-4 mb-4">
        <h2 class="text-sm font-semibold text-black mb-3">Последние темы</h2>
        <div class="space-y-3">
            @foreach ($topics as $topic)
                <a href="{{ route('topics.show', $topic) }}" class="block group">
                    <div class="text-sm font-medium text-black group-hover:underline truncate">{{ $topic->title }}</div>
                    <div class="text-xs text-neutral-400 mt-0.5">{{ $topic->category->name }} &middot; {{ $topic->posts_count }} ответов</div>
                </a>
            @endforeach
        </div>
    </div>
@endif

<div class="border border-neutral-200 rounded-xl p-4">
    <h2 class="text-sm font-semibold text-black mb-3">Стена</h2>

    @auth
        <form method="POST" action="{{ route('wall.store', $user) }}" class="mb-5">
            @csrf
            <textarea name="body" rows="2" required class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black" placeholder="{{ auth()->id() === $user->id ? 'Написать на своей стене...' : 'Написать '.$user->name.'...' }}"></textarea>
            @error('body') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
            <div class="flex justify-end mt-2">
                <button type="submit" class="bg-black text-white text-sm rounded px-4 py-1.5 hover:bg-neutral-800">Отправить</button>
            </div>
        </form>
    @endauth

    <div class="space-y-4">
        @forelse ($wallPosts as $wallPost)
            <div class="flex gap-3 pb-4 border-b border-neutral-200 last:border-b-0 last:pb-0">
                <x-avatar :user="$wallPost->author" class="w-9 h-9 text-sm" />

                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <x-username :user="$wallPost->author" class="text-sm font-semibold" />
                            @if ($wallPost->is_pinned)
                                <span class="text-[10px] uppercase tracking-wide text-neutral-400 shrink-0">закреплено</span>
                            @endif
                        </div>

                        @auth
                            @php
                                $canPin = auth()->id() === $user->id;
                                $canDelete = auth()->id() === $wallPost->author_id || auth()->id() === $user->id || auth()->user()->isModerator();
                            @endphp
                            @if ($canPin || $canDelete)
                                <div class="relative shrink-0">
                                    <button type="button" class="wall-menu-btn text-neutral-400 hover:text-black px-1 leading-none" data-menu="wall-menu-{{ $wallPost->id }}">&middot;&middot;&middot;</button>
                                    <div id="wall-menu-{{ $wallPost->id }}" class="wall-menu hidden absolute right-0 top-full mt-1 z-20 bg-white border border-neutral-200 rounded-lg shadow-sm py-1 min-w-[150px]">
                                        @if ($canPin)
                                            <form method="POST" action="{{ route('wall.pin', $wallPost) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="block w-full text-left px-3 py-1.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black">{{ $wallPost->is_pinned ? 'Открепить' : 'Закрепить' }}</button>
                                            </form>
                                        @endif
                                        @if ($canDelete)
                                            <button type="button" class="block w-full text-left px-3 py-1.5 text-sm text-red-600 hover:bg-neutral-50"
                                                onclick="openDeleteModal('{{ route('wall.destroy', $wallPost) }}', 'Удалить сообщение?', 'Сообщение будет удалено со стены безвозвратно.')">Удалить</button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <div class="text-sm text-neutral-800 whitespace-pre-line leading-relaxed mt-1">{{ $wallPost->body }}</div>
                    <div class="flex items-center gap-3 text-xs text-neutral-400 mt-1">
                        <span>{{ $wallPost->created_at->timezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</span>
                        @auth
                            <button type="button" class="hover:text-black" onclick="document.getElementById('wall-reply-{{ $wallPost->id }}').classList.toggle('hidden')">Ответить</button>
                        @endauth
                    </div>

                    @if ($wallPost->replies->isNotEmpty())
                        <div class="mt-3 space-y-3 border-l border-neutral-200 pl-3">
                            @foreach ($wallPost->replies->sortBy('created_at') as $reply)
                                <div class="flex gap-2">
                                    <x-avatar :user="$reply->author" class="w-7 h-7 text-[11px]" />
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-2">
                                            <x-username :user="$reply->author" class="text-sm font-semibold" />
                                            @auth
                                                @if (auth()->id() === $reply->author_id || auth()->id() === $user->id || auth()->user()->isModerator())
                                                    <button type="button" class="text-xs text-neutral-400 hover:text-red-600 shrink-0"
                                                        onclick="openDeleteModal('{{ route('wall.destroy', $reply) }}', 'Удалить ответ?', 'Ответ будет удалён безвозвратно.')">&times;</button>
                                                @endif
                                            @endauth
                                        </div>
                                        <div class="text-sm text-neutral-800 whitespace-pre-line leading-relaxed">{{ $reply->body }}</div>
                                        <div class="text-xs text-neutral-400 mt-0.5">{{ $reply->created_at->timezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @auth
                        <form id="wall-reply-{{ $wallPost->id }}" method="POST" action="{{ route('wall.store', $user) }}" class="hidden mt-3">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $wallPost->id }}">
                            <textarea name="body" rows="2" required class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black" placeholder="Ваш ответ..."></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="bg-black text-white text-xs rounded px-3 py-1.5 hover:bg-neutral-800">Ответить</button>
                            </div>
                        </form>
                    @endauth
                </div>
            </div>
        @empty
            <div class="text-sm text-neutral-400 text-center py-6">На стене пока пусто</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $wallPosts->links() }}</div>
</div>

<script>
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.wall-menu-btn');
        document.querySelectorAll('.wall-menu').forEach(m => {
            if (!btn || m.id !== btn.dataset.menu) m.classList.add('hidden');
        });
        if (btn) {
            document.getElementById(btn.dataset.menu)?.classList.toggle('hidden');
        }
    });
</script>
@endsection
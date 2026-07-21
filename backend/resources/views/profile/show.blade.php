@extends('layouts.app')

@section('content')
<div class="border border-neutral-200 rounded-2xl overflow-hidden bg-white mb-4">
    <div class="p-6">
        <div class="flex items-start gap-5">
            <x-avatar :user="$user" class="w-28 h-28 text-4xl shadow-sm" />

            <div class="min-w-0 flex-1 pt-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-3xl font-bold {{ $user->roleColor() }} tracking-tight leading-none">{{ $user->name }}</h1>
                    @if ($user->isBanned())
                        <span class="text-[10px] font-semibold uppercase tracking-wide bg-red-50 text-red-600 px-2 py-0.5 rounded">заблокирован</span>
                    @endif
                </div>

                @if ($user->status)
                    <div class="text-[15px] text-neutral-500 mt-2">{{ $user->status }}</div>
                @endif

                @if ($user->birthday || count($user->socialLinks()))
                    <div class="flex items-center gap-2 mt-4 flex-wrap">
                        @if ($user->birthday)
                            <span class="inline-flex items-center gap-1.5 text-xs text-neutral-500 bg-neutral-100 rounded-lg px-2.5 py-1.5">
                                <i class="ti ti-cake text-sm"></i>{{ $user->birthday->format('d.m.Y') }}
                            </span>
                        @endif

                        @php
                            $icons = ['Telegram' => 'ti-brand-telegram', 'ВКонтакте' => 'ti-brand-vk', 'Steam' => 'ti-brand-steam', 'Сайт' => 'ti-link'];
                        @endphp
                        @foreach ($user->socialLinks() as $label => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs text-neutral-600 bg-neutral-100 hover:bg-neutral-200 hover:text-black rounded-lg px-2.5 py-1.5 transition">
                                <i class="ti {{ $icons[$label] ?? 'ti-link' }} text-sm"></i>{{ $label }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @auth
                @if (auth()->id() === $user->id)
                    <a href="{{ route('settings.profile') }}" class="flex items-center gap-1.5 text-sm text-neutral-600 hover:text-black border border-neutral-200 rounded-lg px-3 py-1.5 hover:bg-neutral-50 transition shrink-0">
                        <i class="ti ti-settings text-base"></i> Редактировать
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="grid grid-cols-3 border-t border-neutral-200 divide-x divide-neutral-200 bg-neutral-50/50">
        <div class="px-6 py-3.5">
            <div class="text-xl font-semibold text-black">{{ $user->topics_count }}</div>
            <div class="text-xs text-neutral-500 mt-0.5">тем</div>
        </div>
        <div class="px-6 py-3.5">
            <div class="text-xl font-semibold text-black">{{ $user->posts_count }}</div>
            <div class="text-xs text-neutral-500 mt-0.5">сообщений</div>
        </div>
        <div class="px-6 py-3.5">
            <div class="text-xl font-semibold text-black">{{ $user->created_at->format('d.m.Y') }}</div>
            <div class="text-xs text-neutral-500 mt-0.5">на форуме с</div>
        </div>
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
                                    <button type="button" class="dropdown-btn text-neutral-400 hover:text-black px-1.5 py-0.5 rounded hover:bg-neutral-100 transition leading-none" data-menu="wall-menu-{{ $wallPost->id }}" aria-label="Действия">
                                        <i class="ti ti-dots text-base"></i>
                                    </button>
                                    <div id="wall-menu-{{ $wallPost->id }}" class="dropdown-menu hidden absolute right-0 top-full mt-1 z-20 bg-white border border-neutral-200 rounded-lg shadow-lg py-1 min-w-[160px]">
                                        @if ($canPin)
                                            <form method="POST" action="{{ route('wall.pin', $wallPost) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="flex items-center gap-2.5 w-full text-left px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">
                                                    <i class="ti {{ $wallPost->is_pinned ? 'ti-pinned-off' : 'ti-pin' }} text-base text-neutral-400"></i>
                                                    {{ $wallPost->is_pinned ? 'Открепить' : 'Закрепить' }}
                                                </button>
                                            </form>
                                        @endif
                                        @if ($canDelete)
                                            <button type="button" class="flex items-center gap-2.5 w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-neutral-50 transition"
                                                onclick="openDeleteModal('{{ route('wall.destroy', $wallPost) }}', 'Удалить сообщение?', 'Сообщение будет удалено со стены безвозвратно.')">
                                                <i class="ti ti-trash text-base"></i> Удалить
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <div class="text-sm text-neutral-800 whitespace-pre-line leading-relaxed mt-1">{{ $wallPost->body }}</div>
                    <div class="flex items-center gap-3 text-xs text-neutral-400 mt-1">
                        <x-date :value="$wallPost->created_at" />
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
                                        <div class="text-xs text-neutral-400 mt-0.5"><x-date :value="$reply->created_at" /></div>
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
@endsection
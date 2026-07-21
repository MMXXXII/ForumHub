<div>
    <div id="post-{{ $post->id }}" class="py-4 border-b border-neutral-200">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2 min-w-0">
                <x-username :user="$post->user" class="text-sm font-semibold" />
                @if ($post->moderation_status !== 'approved')
                    <span class="text-[10px] uppercase tracking-wide px-1.5 py-0.5 rounded shrink-0 {{ $post->moderation_status === 'rejected' ? 'bg-red-50 text-red-600' : 'bg-neutral-100 text-neutral-500' }}">
                        {{ $post->moderation_status === 'rejected' ? 'нарушение' : 'на проверке' }}
                    </span>
                @endif
            </div>

            @auth
                @php
                    $canEdit = auth()->id() === $post->user_id;
                    $canDelete = $canEdit || auth()->user()->isModerator();
                @endphp
                @if ($canEdit || $canDelete)
                    <div class="relative shrink-0">
                        <button type="button" class="dropdown-btn text-neutral-400 hover:text-black px-1.5 py-0.5 rounded hover:bg-neutral-100 transition leading-none" data-menu="post-menu-{{ $post->id }}" aria-label="Действия">
                            <i class="ti ti-dots text-base"></i>
                        </button>
                        <div id="post-menu-{{ $post->id }}" class="dropdown-menu hidden absolute right-0 top-full mt-1 z-20 bg-white border border-neutral-200 rounded-lg shadow-lg py-1 min-w-[160px]">
                            @if ($canEdit)
                                <button type="button" class="flex items-center gap-2.5 w-full text-left px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition" onclick="toggleEdit({{ $post->id }})">
                                    <i class="ti ti-pencil text-base text-neutral-400"></i> Изменить
                                </button>
                            @endif
                            @if ($canDelete)
                                <button type="button" class="flex items-center gap-2.5 w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-neutral-50 transition"
                                    onclick="openDeleteModal('{{ route('posts.destroy', $post) }}', 'Удалить сообщение?', 'Сообщение будет удалено безвозвратно.')">
                                    <i class="ti ti-trash text-base"></i> Удалить
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @endauth
        </div>

        <div id="post-body-{{ $post->id }}" class="text-sm text-neutral-800 whitespace-pre-line leading-relaxed">{{ $post->body }}</div>

        @auth
            @if (auth()->id() === $post->user_id)
                <form id="edit-form-{{ $post->id }}" method="POST" action="{{ route('posts.update', $post) }}" class="hidden mt-2">
                    @csrf
                    @method('PATCH')
                    <textarea name="body" rows="3" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">{{ $post->body }}</textarea>
                    <div class="flex items-center gap-2 mt-2">
                        <button type="submit" class="bg-black text-white text-xs rounded px-3 py-1.5 hover:bg-neutral-800">Сохранить</button>
                        <button type="button" class="text-xs text-neutral-500 hover:text-black" onclick="toggleEdit({{ $post->id }})">Отмена</button>
                    </div>
                </form>
            @endif
        @endauth

        <div class="flex items-center gap-3 mt-2 text-xs text-neutral-400">
            <x-date :value="$post->created_at" />
            @if ($post->edited_at)
                <span>изменено <x-date :value="$post->edited_at" /></span>
            @endif
            @auth
                @unless ($topic->is_locked)
                    <button type="button" class="reply-btn hover:text-black" data-id="{{ $post->id }}" data-name="{{ $post->user->name }}">Ответить</button>
                @endunless
            @endauth
        </div>
    </div>

    @if ($post->childrenPosts->isNotEmpty())
        <div class="border-l border-neutral-200 {{ $depth < 6 ? 'pl-4' : 'pl-2' }}">
            @foreach ($post->childrenPosts as $child)
                @include('topics.partials.post', ['post' => $child, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</div>
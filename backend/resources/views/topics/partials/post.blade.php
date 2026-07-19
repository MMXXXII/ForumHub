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
                <div class="flex items-center gap-3">
                    @if (auth()->id() === $post->user_id)
                        <button type="button" class="text-xs text-neutral-400 hover:text-black" onclick="toggleEdit({{ $post->id }})">Изменить</button>
                    @endif
                    @if (auth()->id() === $post->user_id || auth()->user()->isModerator())
                        <button type="button" class="text-xs text-red-600 hover:underline"
                            onclick="openDeleteModal('{{ route('posts.destroy', $post) }}', 'Удалить сообщение?', 'Сообщение будет удалено безвозвратно.')">Удалить</button>
                    @endif
                </div>
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
            <span>{{ $post->created_at->timezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</span>
            @if ($post->edited_at)
                <span class="text-neutral-400">изменено {{ $post->edited_at->timezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</span>
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
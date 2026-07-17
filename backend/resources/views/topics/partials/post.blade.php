<div>
    <div id="post-{{ $post->id }}" class="py-3 {{ $post->is_hidden ? 'opacity-50' : '' }}">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-black">{{ $post->user->name }}</span>
                <span class="text-xs text-neutral-400">{{ $post->created_at->timezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</span>
                @if ($post->is_hidden)
                    <span class="text-[10px] uppercase tracking-wide text-neutral-500">скрыто</span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @auth
                    @if (auth()->user()->isModerator())
                        <form method="POST" action="{{ route('admin.posts.hide', $post) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs text-neutral-400 hover:text-black">{{ $post->is_hidden ? 'Показать' : 'Скрыть' }}</button>
                        </form>
                    @endif
                    @if (auth()->id() === $post->user_id || auth()->user()->isModerator())
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Удалить сообщение?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Удалить</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>

        <div class="text-sm text-neutral-800 whitespace-pre-line leading-relaxed">{{ $post->body }}</div>

        @auth
            @unless ($topic->is_locked)
                <div class="mt-1">
                    <button type="button" class="reply-btn text-xs text-neutral-400 hover:text-black" data-id="{{ $post->id }}" data-name="{{ $post->user->name }}">Ответить</button>
                </div>
            @endunless
        @endauth
    </div>

    @if ($post->childrenPosts->isNotEmpty())
        <div class="border-l border-neutral-200 {{ $depth < 6 ? 'pl-4' : 'pl-2' }}">
            @foreach ($post->childrenPosts as $child)
                @include('topics.partials.post', ['post' => $child, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</div>
@extends('layouts.app')

@section('content')
<nav class="text-xs text-neutral-400 mb-3">
    <a href="{{ route('home') }}" class="hover:text-black">Форум</a>
    <span class="mx-1">/</span>
    <a href="{{ route('categories.show', $topic->category) }}" class="hover:text-black">{{ $topic->category->name }}</a>
</nav>

<div class="mb-6 border-b border-neutral-200 pb-4">
    <h1 class="text-2xl font-semibold text-black">{{ $topic->title }}</h1>

    <h2 class="mt-1 text-sm font-medium">
        <span class="text-neutral-400">в разделе</span>
        <a href="{{ route('categories.show', $topic->category) }}" class="text-black hover:underline">{{ $topic->category->name }}</a>
    </h2>

    <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-neutral-400">
        <span>Автор: <span class="text-neutral-600">{{ $topic->user->name }}</span></span>
        <span>·</span>
        <span>{{ $topic->created_at->timezone('Asia/Irkutsk')->format('d.m.Y, H:i') }}</span>
        <span>·</span>
        <span>{{ $topic->views }} просмотров</span>
        @if ($topic->is_locked)
            <span>·</span>
            <span class="uppercase tracking-wide bg-neutral-200 text-neutral-600 px-1.5 py-0.5 rounded text-[10px]">закрыта</span>
        @endif
    </div>

    @auth
        @if (auth()->id() === $topic->user_id || auth()->user()->isModerator())
            <form method="POST" action="{{ route('topics.destroy', $topic) }}" onsubmit="return confirm('Удалить тему целиком?')" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-600 hover:underline">Удалить тему</button>
            </form>
        @endif
    @endauth
</div>


<div class="space-y-3">
    @forelse ($rootPosts as $post)
        @include('topics.partials.post', ['post' => $post, 'depth' => 0])
    @empty
        <div class="text-sm text-neutral-400 text-center py-8">Пока нет сообщений. Будьте первым!</div>
    @endforelse
</div>

@auth
    @if ($topic->is_locked)
        <div class="mt-6 text-sm text-neutral-400 border border-neutral-200 rounded-lg p-4 text-center">Тема закрыта для ответов.</div>
    @else
        <form method="POST" action="{{ route('posts.store', $topic) }}" class="mt-6" id="replyForm">
            @csrf
            <input type="hidden" name="parent_id" id="parentId" value="{{ old('parent_id') }}">

            <div id="replyingTo" class="hidden items-center justify-between text-xs text-neutral-500 bg-neutral-100 rounded px-3 py-2 mb-2">
                <span>Ответ пользователю <span id="replyingToName" class="font-medium"></span></span>
                <button type="button" id="cancelReply" class="text-neutral-400 hover:text-black">отменить</button>
            </div>

            @error('body')
                <div class="mb-2 text-xs text-red-600">{{ $message }}</div>
            @enderror

            <textarea name="body" rows="1" required id="replyBody" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm resize-none overflow-hidden focus:outline-none focus:border-black" placeholder="Написать сообщение...">{{ old('body') }}</textarea>
            <div class="mt-2 flex justify-end">
                <button type="submit" class="bg-black text-white text-sm rounded px-4 py-2 hover:bg-neutral-800">Отправить</button>
            </div>
        </form>
    @endif
@else
    <div class="mt-6 text-sm text-neutral-500 border border-neutral-200 rounded-lg p-4 text-center">
        <a href="{{ route('login') }}" class="text-black underline">Войдите</a>, чтобы ответить.
    </div>
@endauth

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('replyForm');
        if (!form) return;
        const parentInput = document.getElementById('parentId');
        const banner = document.getElementById('replyingTo');
        const nameEl = document.getElementById('replyingToName');
        const textarea = form.querySelector('textarea[name="body"]');

        document.querySelectorAll('.reply-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                parentInput.value = btn.dataset.id;
                nameEl.textContent = btn.dataset.name;
                banner.classList.remove('hidden');
                banner.classList.add('flex');
                textarea.focus();
                form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

            document.getElementById('cancelReply')?.addEventListener('click', () => {
            parentInput.value = '';
            banner.classList.add('hidden');
            banner.classList.remove('flex');
        });

        const autogrow = (el) => {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        };
        if (textarea) {
            autogrow(textarea);
            textarea.addEventListener('input', () => autogrow(textarea));
        }

        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Отправка...';
            }
        });
    });
</script>
@endsection
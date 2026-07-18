<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ForumHub')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-neutral-900 min-h-screen antialiased">
    <header class="border-b border-neutral-200 sticky top-0 bg-white/95 backdrop-blur z-10">
        <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-lg font-bold text-black tracking-tight">ForumHub</a>

            <div class="hidden md:block flex-1 max-w-md mx-8">
                <input type="text" placeholder="Поиск по форуму" class="w-full bg-neutral-50 border border-neutral-200 rounded px-3 py-1.5 text-sm text-neutral-900 placeholder-neutral-400 focus:outline-none focus:border-neutral-400 focus:bg-white">
            </div>

            <nav class="flex items-center gap-3 text-sm">
                <div class="relative group hidden sm:block">
                    <button type="button" class="text-neutral-500 hover:text-black transition inline-flex items-center gap-1">
                        Соц. сети
                        <span class="text-[10px] leading-none">&#9662;</span>
                    </button>
                    <div class="hidden group-hover:block absolute right-0 top-full pt-2 z-50">
                        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm py-1 min-w-[180px]">
                            <a href="https://t.me/MMXXXII" target="_blank" rel="noopener noreferrer" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">Telegram</a>
                            <a href="https://vk.ru/mmxxxii" target="_blank" rel="noopener noreferrer" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">ВКонтакте</a>
                            <a href="https://github.com/MMXXXII" target="_blank" rel="noopener noreferrer" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">GitHub</a>
                        </div>
                    </div>
                </div>

                @auth
                    @if (auth()->user()->isModerator())
                        <a href="{{ route('admin.dashboard') }}" class="text-neutral-500 hover:text-black transition">Панель</a>
                    @endif
                    <x-username :user="auth()->user()" />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-neutral-500 hover:text-black transition">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-neutral-500 hover:text-black transition">Войти</a>
                    <a href="{{ route('register') }}" class="bg-black text-white font-medium px-3 py-1.5 rounded hover:bg-neutral-800 transition">Регистрация</a>
                @endauth
            </nav>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 py-6 flex gap-6 items-start">
        <aside class="hidden md:block w-56 shrink-0 sticky top-20 space-y-5">
            @auth
                <a href="{{ route('topics.create') }}" class="block bg-black text-white text-sm font-medium text-center rounded-lg px-3 py-2 hover:bg-neutral-800 transition">Создать тему</a>
            @else
                <a href="{{ route('login') }}" class="block bg-black text-white text-sm font-medium text-center rounded-lg px-3 py-2 hover:bg-neutral-800 transition">Войдите и обсудите</a>
            @endauth

            <div>
                <a href="{{ route('home') }}" class="block px-3 py-2 text-sm rounded-lg border border-neutral-200 mb-3 {{ request()->routeIs('home') ? 'bg-neutral-50 text-black font-medium' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    Все обсуждения
                </a>

                <div class="text-xs uppercase text-neutral-400 font-medium mb-2 px-1">Разделы</div>
                <nav class="border border-neutral-200 rounded-lg overflow-hidden">
                    @foreach ($sidebarCategories as $category)
                        <a href="{{ route('categories.show', $category) }}" class="block px-3 py-2 text-sm border-b border-neutral-200 last:border-b-0 hover:bg-neutral-50 hover:text-black transition {{ request()->is('c/'.$category->slug) ? 'bg-neutral-50 text-black font-medium' : 'text-neutral-700' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </nav>
            </div>
            <div>
                <div class="text-xs uppercase text-neutral-400 font-medium mb-2 px-1">Участники</div>
                <div class="border border-neutral-200 rounded-lg overflow-hidden">
                    @foreach ($sidebarUsers as $sidebarUser)
                        <div class="flex items-center justify-between px-3 py-2 border-b border-neutral-200 last:border-b-0">
                            <x-username :user="$sidebarUser" class="text-sm truncate" />
                            <span class="text-[11px] text-neutral-400 shrink-0 ml-2">{{ $sidebarUser->role }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="flex-1 min-w-0">
            @yield('content')
        </main>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 px-4">
        <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-5">
            <h3 id="deleteModalTitle" class="text-base font-semibold text-black">Удалить?</h3>
            <p id="deleteModalText" class="text-sm text-neutral-500 mt-1">Действие необратимо.</p>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeDeleteModal()" class="text-sm text-neutral-600 hover:text-black px-3 py-1.5">Отмена</button>
                <form id="deleteModalForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white text-sm rounded px-4 py-1.5 hover:bg-red-700">Удалить</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(action, title, text) {
            const modal = document.getElementById('deleteModal');
            document.getElementById('deleteModalForm').action = action;
            document.getElementById('deleteModalTitle').textContent = title || 'Удалить?';
            document.getElementById('deleteModalText').textContent = text || 'Действие необратимо.';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        function toggleEdit(id) {
            document.getElementById('edit-form-' + id)?.classList.toggle('hidden');
            document.getElementById('post-body-' + id)?.classList.toggle('hidden');
        }
        document.addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') closeDeleteModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
</body>
</html>
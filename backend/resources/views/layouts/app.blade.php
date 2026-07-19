<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ForumHub')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-neutral-900 min-h-screen antialiased">
    <header class="border-b border-neutral-200 sticky top-0 bg-white/90 backdrop-blur-md z-30">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                <span class="w-8 h-8 rounded-lg bg-black text-white flex items-center justify-center text-sm font-bold">F</span>
                <span class="text-base font-bold text-black tracking-tight hidden sm:block">ForumHub</span>
            </a>

            <div class="hidden md:block flex-1 max-w-md">
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-base"></i>
                    <input type="text" placeholder="Поиск по форуму" class="w-full bg-neutral-100 border border-transparent rounded-lg pl-9 pr-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:outline-none focus:bg-white focus:border-neutral-300 transition">
                </div>
            </div>

            <nav class="flex items-center gap-1 text-sm ml-auto">
                <div class="relative group hidden sm:block">
                    <button type="button" class="text-neutral-600 hover:text-black hover:bg-neutral-100 rounded-lg px-3 py-2 transition inline-flex items-center gap-1.5">
                        <i class="ti ti-world text-base"></i>
                        <span>Соц. сети</span>
                    </button>
                    <div class="hidden group-hover:block absolute right-0 top-full pt-2 z-50">
                        <div class="bg-white border border-neutral-200 rounded-xl shadow-lg py-1.5 min-w-[190px]">
                            <a href="https://t.me/MMXXXII" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">
                                <i class="ti ti-brand-telegram text-base text-neutral-400"></i> Telegram
                            </a>
                            <a href="https://vk.ru/mmxxxii" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">
                                <i class="ti ti-brand-vk text-base text-neutral-400"></i> ВКонтакте
                            </a>
                            <a href="https://github.com/MMXXXII" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">
                                <i class="ti ti-brand-github text-base text-neutral-400"></i> GitHub
                            </a>
                        </div>
                    </div>
                </div>

                @auth
                    @if (auth()->user()->isModerator())
                        <a href="{{ route('admin.dashboard') }}" class="text-neutral-600 hover:text-black hover:bg-neutral-100 rounded-lg px-3 py-2 transition inline-flex items-center gap-1.5">
                            <i class="ti ti-layout-dashboard text-base"></i>
                            <span class="hidden lg:inline">Панель</span>
                        </a>
                    @endif

                    <div class="relative group">
                        <button type="button" class="flex items-center gap-2 hover:bg-neutral-100 rounded-lg px-2 py-1.5 transition">
                            <x-avatar :user="auth()->user()" class="w-7 h-7 text-xs" />
                            <x-username :user="auth()->user()" class="text-sm hidden sm:block" :link="false" />
                            <i class="ti ti-chevron-down text-xs text-neutral-400"></i>
                        </button>
                        <div class="hidden group-hover:block absolute right-0 top-full pt-2 z-50">
                            <div class="bg-white border border-neutral-200 rounded-xl shadow-lg py-1.5 min-w-[190px]">
                                <a href="{{ route('profile.show', auth()->user()) }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">
                                    <i class="ti ti-user text-base text-neutral-400"></i> Мой профиль
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">
                                    <i class="ti ti-settings text-base text-neutral-400"></i> Настройки
                                </a>
                                <div class="h-px bg-neutral-200 my-1.5"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2.5 w-full text-left px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-black transition">
                                        <i class="ti ti-logout text-base text-neutral-400"></i> Выйти
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-neutral-600 hover:text-black hover:bg-neutral-100 rounded-lg px-3 py-2 transition">Войти</a>
                    <a href="{{ route('register') }}" class="bg-black text-white font-medium px-4 py-2 rounded-lg hover:bg-neutral-800 transition">Регистрация</a>
                @endauth
            </nav>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 py-6 flex gap-6 items-start">
        <aside class="hidden md:block w-60 shrink-0 sticky top-20 space-y-6">
            @auth
                <a href="{{ route('topics.create') }}" class="flex items-center justify-center gap-2 bg-black text-white text-sm font-medium rounded-xl px-4 py-2.5 hover:bg-neutral-800 transition shadow-sm">
                    <i class="ti ti-plus text-base"></i> Создать тему
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 bg-black text-white text-sm font-medium rounded-xl px-4 py-2.5 hover:bg-neutral-800 transition shadow-sm">
                    <i class="ti ti-message-plus text-base"></i> Войдите и обсудите
                </a>
            @endauth

            <nav class="space-y-0.5">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('home') ? 'bg-neutral-100 text-black font-medium' : 'text-neutral-600 hover:bg-neutral-50 hover:text-black' }}">
                    <i class="ti ti-messages text-base {{ request()->routeIs('home') ? 'text-black' : 'text-neutral-400' }}"></i>
                    Все обсуждения
                </a>
            </nav>

            <div>
                <div class="text-[11px] uppercase tracking-wider text-neutral-400 font-semibold mb-2 px-3">Разделы</div>
                <nav class="space-y-0.5">
                    @foreach ($sidebarCategories as $category)
                        @php $active = request()->is('c/'.$category->slug); @endphp
                        <a href="{{ route('categories.show', $category) }}" class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg text-sm transition {{ $active ? 'bg-neutral-100 text-black font-medium' : 'text-neutral-600 hover:bg-neutral-50 hover:text-black' }}">
                            <span class="truncate">{{ $category->name }}</span>
                            <span class="text-xs text-neutral-400 shrink-0">{{ $category->topics_count }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <div>
                <div class="text-[11px] uppercase tracking-wider text-neutral-400 font-semibold mb-2 px-3">Участники</div>
                <div class="space-y-0.5">
                    @foreach ($sidebarUsers as $sidebarUser)
                        <a href="{{ route('profile.show', $sidebarUser) }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg hover:bg-neutral-50 transition min-w-0">
                            <x-avatar :user="$sidebarUser" class="w-7 h-7 text-[11px]" />
                            <div class="min-w-0">
                                <x-username :user="$sidebarUser" class="text-sm block truncate" :link="false" />
                                <div class="text-[11px] text-neutral-400 leading-tight">{{ $sidebarUser->role }}</div>
                            </div>
                        </a>
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
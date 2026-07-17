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
                    <span class="text-neutral-500">{{ auth()->user()->name }}</span>
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
        <aside class="hidden md:block w-56 shrink-0 sticky top-20">
            <div class="text-xs uppercase text-neutral-400 font-medium mb-2 px-1">Разделы</div>
            <nav class="border border-neutral-200 rounded-lg overflow-hidden">
                @foreach ($sidebarCategories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="block px-3 py-2 text-sm text-neutral-700 border-b border-neutral-200 last:border-b-0 hover:bg-neutral-50 hover:text-black transition">
                        {{ $category->name }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <main class="flex-1 min-w-0">
            @yield('content')
        </main>
    </div>
</body>
</html>
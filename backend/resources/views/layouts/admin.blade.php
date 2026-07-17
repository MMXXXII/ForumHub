<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админ-панель — ForumHub')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>
<body class="bg-neutral-50 text-neutral-900 min-h-screen antialiased">
    <div class="flex min-h-screen">
        <aside class="w-60 shrink-0 bg-white border-r border-neutral-200 flex flex-col">
            <div class="h-14 flex items-center px-4 border-b border-neutral-200">
                <a href="{{ route('home') }}" class="text-lg font-bold text-black">ForumHub</a>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-1 text-sm">
                <div class="text-xs uppercase text-neutral-400 font-medium px-2 mb-1 mt-2">Обзор</div>
                <a href="{{ route('admin.dashboard') }}" class="block px-2 py-1.5 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">Дашборд</a>

                <div class="text-xs uppercase text-neutral-400 font-medium px-2 mb-1 mt-4">Модерация</div>
                <a href="{{ route('admin.topics.index') }}" class="block px-2 py-1.5 rounded {{ request()->routeIs('admin.topics.*') ? 'bg-black text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">Темы</a>
                <a href="{{ route('admin.posts.index') }}" class="block px-2 py-1.5 rounded {{ request()->routeIs('admin.posts.*') ? 'bg-black text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">Сообщения</a>

                @if (auth()->user()->isAdmin())
                    <div class="text-xs uppercase text-neutral-400 font-medium px-2 mb-1 mt-4">Управление</div>
                    <a href="{{ route('admin.users.index') }}" class="block px-2 py-1.5 rounded {{ request()->routeIs('admin.users.*') ? 'bg-black text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">Пользователи</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-2 py-1.5 rounded {{ request()->routeIs('admin.categories.*') ? 'bg-black text-white' : 'text-neutral-700 hover:bg-neutral-100' }}">Разделы</a>
                @endif
            </nav>

            <div class="px-4 py-3 border-t border-neutral-200 text-xs text-neutral-500">
                {{ auth()->user()->name }} &middot; {{ auth()->user()->role }}
            </div>
        </aside>

        <main class="flex-1 min-w-0 p-6">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded px-3 py-2">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ForumHub')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-200 min-h-screen">
    <header class="border-b border-slate-800">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-semibold text-white">ForumHub</a>
            <nav class="flex gap-4 text-sm text-slate-400">
                <a href="#" class="hover:text-white">Войти</a>
                <a href="#" class="hover:text-white">Регистрация</a>
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>
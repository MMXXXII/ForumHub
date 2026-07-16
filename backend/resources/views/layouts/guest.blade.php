<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ForumHub')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-neutral-900 min-h-screen antialiased flex items-center justify-center">
    <div class="w-full max-w-sm px-4">
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-black tracking-tight">ForumHub</a>
        </div>
        <div class="border border-neutral-200 rounded-lg p-6 shadow-sm">
            @yield('content')
        </div>
    </div>
</body>
</html>
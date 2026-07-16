@extends('layouts.guest')

@section('content')
<h1 class="text-lg font-semibold text-black mb-4">Вход</h1>

@if ($errors->any())
    <div class="mb-4 text-sm text-red-600 space-y-1">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-neutral-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-neutral-400">
    </div>

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Пароль</label>
        <input type="password" name="password" class="w-full border border-neutral-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-neutral-400">
    </div>

    <label class="flex items-center gap-2 text-sm text-neutral-600">
        <input type="checkbox" name="remember" class="rounded border-neutral-300">
        Запомнить меня
    </label>

    <button type="submit" class="w-full bg-black text-white font-medium py-2 rounded hover:bg-neutral-800 transition">Войти</button>
</form>

<div class="text-center text-sm text-neutral-500 mt-4">
    Нет аккаунта? <a href="{{ route('register') }}" class="text-black hover:underline">Зарегистрироваться</a>
</div>
@endsection
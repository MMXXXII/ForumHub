@extends('layouts.guest')

@section('content')
<h1 class="text-lg font-semibold text-black mb-4">Регистрация</h1>

@if ($errors->any())
    <div class="mb-4 text-sm text-red-600 space-y-1">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Имя пользователя</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-neutral-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-neutral-400">
    </div>

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-neutral-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-neutral-400">
    </div>

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Пароль</label>
        <input type="password" name="password" class="w-full border border-neutral-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-neutral-400">
    </div>

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Повторите пароль</label>
        <input type="password" name="password_confirmation" class="w-full border border-neutral-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-neutral-400">
    </div>

    <button type="submit" class="w-full bg-black text-white font-medium py-2 rounded hover:bg-neutral-800 transition">Зарегистрироваться</button>
</form>

<div class="text-center text-sm text-neutral-500 mt-4">
    Уже есть аккаунт? <a href="{{ route('login') }}" class="text-black hover:underline">Войти</a>
</div>
@endsection
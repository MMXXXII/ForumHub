@extends('settings.layout')

@section('settings')
<div class="border border-neutral-200 rounded-xl p-4 mb-6">
    <div class="text-sm font-medium text-black mb-1">Данные аккаунта</div>
    <div class="text-sm text-neutral-500">{{ $user->email }}</div>
    <div class="text-xs text-neutral-400 mt-2">Почту изменить нельзя — обратитесь к администратору.</div>
</div>

<form method="POST" action="{{ route('settings.password.update') }}" class="border border-neutral-200 rounded-xl p-4 space-y-4">
    @csrf
    @method('PATCH')

    <div class="text-sm font-medium text-black">Смена пароля</div>

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Текущий пароль</label>
        <input type="password" name="current_password" required autocomplete="current-password" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
        @error('current_password') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Новый пароль</label>
        <input type="password" name="password" required autocomplete="new-password" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
        <div class="text-xs text-neutral-400 mt-1">Минимум 8 символов, буквы разного регистра и цифры.</div>
        @error('password') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Повторите новый пароль</label>
        <input type="password" name="password_confirmation" required autocomplete="new-password" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
    </div>

    <button type="submit" class="bg-black text-white text-sm rounded-lg px-4 py-2 hover:bg-neutral-800 transition">Изменить пароль</button>
</form>
@endsection
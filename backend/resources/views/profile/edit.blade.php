@extends('layouts.app')

@section('content')
<div class="max-w-lg">
    <h1 class="text-xl font-semibold text-black mb-6">Редактировать профиль</h1>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PATCH')

        <div class="flex items-center gap-4">
            <x-avatar :user="$user" class="w-16 h-16 text-xl" />
            <div class="flex-1">
                <label class="block text-sm text-neutral-600 mb-1">Аватар</label>
                <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:bg-black file:text-white hover:file:bg-neutral-800">
                @error('avatar') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm text-neutral-600 mb-1">Статус</label>
            <input type="text" name="status" value="{{ old('status', $user->status) }}" maxlength="100" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black" placeholder="Пара слов о себе">
            @error('status') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-black text-white text-sm rounded px-4 py-2 hover:bg-neutral-800">Сохранить</button>
            <a href="{{ route('profile.show', $user) }}" class="text-sm text-neutral-500 hover:text-black">Отмена</a>
        </div>
    </form>
</div>
@endsection
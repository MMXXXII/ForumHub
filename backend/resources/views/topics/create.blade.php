@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-semibold text-black mb-6">Новая тема</h1>

    <form method="POST" action="{{ route('topics.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm text-neutral-600 mb-1">Раздел</label>
            <select name="category_id" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', request('category')) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm text-neutral-600 mb-1">Заголовок</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black" placeholder="О чём тема?">
            @error('title') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm text-neutral-600 mb-1">Сообщение</label>
            <textarea name="body" rows="6" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black" placeholder="Текст первого сообщения...">{{ old('body') }}</textarea>
            @error('body') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-black text-white text-sm rounded px-4 py-2 hover:bg-neutral-800 transition">Создать тему</button>
            <a href="{{ url()->previous() }}" class="text-sm text-neutral-500 hover:text-black">Отмена</a>
        </div>
    </form>
</div>
@endsection

<script>
    document.querySelector('form')?.addEventListener('submit', (e) => {
        const btn = e.target.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Создаём...';
        }
    });
</script>
@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-semibold text-black mb-6">Разделы</h1>


<div class="border border-neutral-200 rounded-lg overflow-hidden mb-6">
    @foreach ($categories as $category)
        <div class="px-4 py-3 border-b border-neutral-200 last:border-b-0 space-y-2">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <input type="text" name="name" value="{{ $category->name }}" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1">
                <input type="text" name="description" value="{{ $category->description }}" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1">
                <input type="number" name="order" value="{{ $category->order }}" class="border border-neutral-200 rounded px-2 py-1 text-sm w-16">
                <button type="submit" class="text-xs border border-neutral-200 rounded px-2 py-1 hover:bg-neutral-50">Сохранить</button>
            </form>
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Удалить раздел вместе со всеми темами?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-600 hover:underline">Удалить ({{ $category->topics_count }} тем)</button>
            </form>
        </div>
    @endforeach
</div>

<div class="border border-neutral-200 rounded-lg p-4">
    <div class="text-sm font-medium text-black mb-3">Новый раздел</div>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="flex items-center gap-2">
        @csrf
        <input type="text" name="name" placeholder="Название" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1">
        <input type="text" name="description" placeholder="Описание" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1">
        <input type="number" name="order" placeholder="0" class="border border-neutral-200 rounded px-2 py-1 text-sm w-16">
        <button type="submit" class="text-xs bg-black text-white rounded px-3 py-1.5 hover:bg-neutral-800">Создать</button>
    </form>
</div>
@endsection
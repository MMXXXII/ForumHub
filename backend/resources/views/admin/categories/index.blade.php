@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-semibold text-black mb-6">Разделы</h1>

<div class="border border-neutral-200 rounded-lg overflow-hidden mb-6 bg-white">
    <div class="flex items-center gap-2 px-4 py-2 bg-neutral-50 border-b border-neutral-200 text-[11px] font-semibold uppercase tracking-wider text-neutral-400">
        <span class="flex-1">Название</span>
        <span class="flex-1">Описание</span>
        <span class="w-20 text-center">Порядок</span>
        <span class="w-24"></span>
        <span class="w-8"></span>
    </div>

    @foreach ($categories as $category)
        <div class="flex items-center gap-2 px-4 py-2.5 border-b border-neutral-200 last:border-b-0">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex items-center gap-2 flex-1 min-w-0">
                @csrf
                @method('PATCH')
                <input type="text" name="name" value="{{ $category->name }}" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1 min-w-0 focus:outline-none focus:border-black">
                <input type="text" name="description" value="{{ $category->description }}" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1 min-w-0 focus:outline-none focus:border-black">
                <input type="number" name="order" value="{{ $category->order }}" title="Чем меньше число, тем выше раздел в списке" class="border border-neutral-200 rounded px-2 py-1 text-sm w-20 text-center focus:outline-none focus:border-black">
                <button type="submit" class="w-24 text-xs border border-neutral-200 rounded px-2 py-1 hover:bg-neutral-50 shrink-0">Сохранить</button>
            </form>

            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="w-8 shrink-0 flex justify-center" onsubmit="return confirm('Удалить раздел «{{ $category->name }}» вместе со всеми темами ({{ $category->topics_count }})?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-neutral-400 hover:text-red-600 transition" title="Удалить раздел ({{ $category->topics_count }} тем)">
                    <i class="ti ti-trash text-base"></i>
                </button>
            </form>
        </div>
    @endforeach
</div>

<div class="border border-neutral-200 rounded-lg p-4 bg-white">
    <div class="text-sm font-medium text-black mb-3">Новый раздел</div>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="flex items-center gap-2">
        @csrf
        <input type="text" name="name" placeholder="Название" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1 min-w-0 focus:outline-none focus:border-black">
        <input type="text" name="description" placeholder="Описание" class="border border-neutral-200 rounded px-2 py-1 text-sm flex-1 min-w-0 focus:outline-none focus:border-black">
        <input type="number" name="order" placeholder="0" title="Чем меньше число, тем выше раздел в списке" class="border border-neutral-200 rounded px-2 py-1 text-sm w-20 text-center focus:outline-none focus:border-black">
        <button type="submit" class="w-24 text-xs bg-black text-white rounded px-3 py-1.5 hover:bg-neutral-800 shrink-0">Создать</button>
    </form>
</div>
@endsection
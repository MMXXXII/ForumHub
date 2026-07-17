@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold text-black mb-6">Панель управления</h1>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="border border-neutral-200 rounded-lg p-4">
        <div class="text-2xl font-semibold text-black">{{ $stats['users'] }}</div>
        <div class="text-xs text-neutral-500 mt-1">пользователей</div>
    </div>
    <div class="border border-neutral-200 rounded-lg p-4">
        <div class="text-2xl font-semibold text-black">{{ $stats['topics'] }}</div>
        <div class="text-xs text-neutral-500 mt-1">тем</div>
    </div>
    <div class="border border-neutral-200 rounded-lg p-4">
        <div class="text-2xl font-semibold text-black">{{ $stats['posts'] }}</div>
        <div class="text-xs text-neutral-500 mt-1">сообщений</div>
    </div>
    <div class="border border-neutral-200 rounded-lg p-4">
        <div class="text-2xl font-semibold text-black">{{ $stats['hidden_posts'] }}</div>
        <div class="text-xs text-neutral-500 mt-1">скрытых сообщений</div>
    </div>
</div>

@if (auth()->user()->isAdmin())
    <div class="flex gap-3 text-sm">
        <a href="{{ route('admin.users.index') }}" class="border border-neutral-200 rounded px-3 py-2 hover:bg-neutral-50">Пользователи</a>
        <a href="{{ route('admin.categories.index') }}" class="border border-neutral-200 rounded px-3 py-2 hover:bg-neutral-50">Разделы</a>
    </div>
@endif
@endsection
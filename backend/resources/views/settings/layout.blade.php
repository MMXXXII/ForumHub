@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold text-black mb-4">Настройки</h1>

@if (session('status'))
    <div class="mb-4 flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
        <i class="ti ti-check text-base"></i>{{ session('status') }}
    </div>
@endif

<div class="flex items-center gap-1 border-b border-neutral-200 mb-6">
    @php
        $tabs = [
            'settings.profile' => ['Профиль', 'ti-user'],
            'settings.security' => ['Безопасность', 'ti-lock'],
            'settings.preferences' => ['Предпочтения', 'ti-adjustments'],
        ];
    @endphp
    @foreach ($tabs as $route => [$label, $icon])
        <a href="{{ route($route) }}" class="flex items-center gap-1.5 px-3 py-2 text-sm border-b-2 -mb-px transition {{ request()->routeIs($route) ? 'border-black text-black font-medium' : 'border-transparent text-neutral-500 hover:text-black' }}">
            <i class="ti {{ $icon }} text-base"></i>{{ $label }}
        </a>
    @endforeach
</div>

<div class="max-w-2xl">
    @yield('settings')
</div>
@endsection
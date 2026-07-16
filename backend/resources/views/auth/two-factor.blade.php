@extends('layouts.guest')

@section('content')
<h1 class="text-lg font-semibold text-black mb-2">Подтверждение входа</h1>
<p class="text-sm text-neutral-500 mb-4">Мы отправили код на вашу почту. Введите его ниже.</p>

@if ($errors->any())
    <div class="mb-4 text-sm text-red-600 space-y-1">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('two-factor.store') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm text-neutral-600 mb-1">Код из письма</label>
        <input type="text" name="code" maxlength="6" class="w-full border border-neutral-200 rounded px-3 py-2 text-sm text-center tracking-widest text-lg focus:outline-none focus:border-neutral-400" autofocus>
    </div>

    <button type="submit" class="w-full bg-black text-white font-medium py-2 rounded hover:bg-neutral-800 transition">Подтвердить</button>
</form>
@endsection
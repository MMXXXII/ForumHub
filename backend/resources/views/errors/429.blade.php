@extends('layouts.app')

@section('content')
<div class="border border-neutral-200 rounded-xl py-16 text-center bg-white">
    <i class="ti ti-clock-pause text-4xl text-neutral-300"></i>
    <h1 class="text-lg font-semibold text-black mt-3">Слишком много запросов</h1>
    <p class="text-sm text-neutral-500 mt-1">Вы отправляете сообщения слишком часто. Подождите немного и попробуйте снова.</p>
    <a href="{{ url()->previous() }}" class="inline-block mt-4 text-sm text-neutral-600 hover:text-black border border-neutral-200 rounded-lg px-4 py-2 hover:bg-neutral-50 transition">Вернуться</a>
</div>
@endsection
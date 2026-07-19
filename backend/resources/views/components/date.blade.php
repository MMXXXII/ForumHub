@props(['value', 'format' => 'd.m.Y H:i'])

@php
    $tz = auth()->check() ? auth()->user()->timezone : config('app.display_timezone', 'Asia/Irkutsk');
@endphp

<span {{ $attributes }}>{{ \Illuminate\Support\Carbon::parse($value)->timezone($tz)->format($format) }}</span>
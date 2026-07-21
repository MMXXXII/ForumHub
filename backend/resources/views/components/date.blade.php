@props(['value', 'format' => 'd.m.Y H:i', 'human' => false])

@php
    $tz = auth()->check() ? auth()->user()->timezone : config('app.display_timezone', 'Asia/Irkutsk');
    $date = \Illuminate\Support\Carbon::parse($value)->timezone($tz);
@endphp

<span {{ $attributes->merge(['title' => $date->format('d.m.Y H:i')]) }}>{{ $human ? $date->diffForHumans() : $date->format($format) }}</span>
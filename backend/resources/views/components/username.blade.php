@props(['user', 'link' => true, 'card' => true])

@php
    $cardData = [
        'name' => $user->name,
        'url' => route('profile.show', $user),
        'avatar' => $user->avatarUrl(),
        'initial' => mb_strtoupper(mb_substr($user->name, 0, 1)),
        'role' => $user->roleLabel(),
        'color' => $user->roleColor(),
        'status' => $user->status,
        'joined' => $user->created_at->format('d.m.Y'),
        'banned' => $user->isBanned(),
    ];
@endphp

@if ($link)
    <a href="{{ route('profile.show', $user) }}" @if($card) data-usercard="{{ json_encode($cardData, JSON_UNESCAPED_UNICODE) }}" @endif {{ $attributes->merge(['class' => 'font-medium hover:underline '.$user->roleColor()]) }}>{{ $user->name }}</a>
@else
    <span @if($card) data-usercard="{{ json_encode($cardData, JSON_UNESCAPED_UNICODE) }}" @endif {{ $attributes->merge(['class' => 'font-medium '.$user->roleColor()]) }}>{{ $user->name }}</span>
@endif
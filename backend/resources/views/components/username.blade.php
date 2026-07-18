@props(['user', 'link' => true])

@if ($link)
    <a href="{{ route('profile.show', $user) }}" {{ $attributes->merge(['class' => 'font-medium hover:underline '.$user->roleColor()]) }}>{{ $user->name }}</a>
@else
    <span {{ $attributes->merge(['class' => 'font-medium '.$user->roleColor()]) }}>{{ $user->name }}</span>
@endif
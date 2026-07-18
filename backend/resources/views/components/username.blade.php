@props(['user'])

<span {{ $attributes->merge(['class' => 'font-medium '.$user->roleColor()]) }}>{{ $user->name }}</span>
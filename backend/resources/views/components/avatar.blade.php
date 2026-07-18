@props(['user'])

@if ($user->avatarUrl())
    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" {{ $attributes->merge(['class' => 'rounded-lg object-cover bg-neutral-100 shrink-0']) }}>
@else
    <div {{ $attributes->merge(['class' => 'rounded-lg bg-neutral-100 text-neutral-500 flex items-center justify-center font-semibold shrink-0']) }}>{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</div>
@endif
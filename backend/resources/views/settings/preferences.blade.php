@extends('settings.layout')

@section('settings')
<form method="POST" action="{{ route('settings.preferences.update') }}" class="border border-neutral-200 rounded-xl p-4 space-y-4">
    @csrf
    @method('PATCH')

    <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">Часовой пояс</label>
        <select name="timezone" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
            @php
                $zones = [
                    'Europe/Kaliningrad' => 'Калининград (UTC+2)',
                    'Europe/Moscow' => 'Москва (UTC+3)',
                    'Europe/Samara' => 'Самара (UTC+4)',
                    'Asia/Yekaterinburg' => 'Екатеринбург (UTC+5)',
                    'Asia/Omsk' => 'Омск (UTC+6)',
                    'Asia/Krasnoyarsk' => 'Красноярск (UTC+7)',
                    'Asia/Irkutsk' => 'Иркутск (UTC+8)',
                    'Asia/Yakutsk' => 'Якутск (UTC+9)',
                    'Asia/Vladivostok' => 'Владивосток (UTC+10)',
                    'Asia/Magadan' => 'Магадан (UTC+11)',
                    'Asia/Kamchatka' => 'Камчатка (UTC+12)',
                    'UTC' => 'UTC',
                ];
            @endphp
            @foreach ($zones as $tz => $label)
                <option value="{{ $tz }}" @selected($user->timezone === $tz)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="text-xs text-neutral-400 mt-1">Все даты и время на форуме будут показаны в этом поясе.</div>
        @error('timezone') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="bg-black text-white text-sm rounded-lg px-4 py-2 hover:bg-neutral-800 transition">Сохранить</button>
</form>
@endsection
@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-semibold text-black mb-6">Дашборд</h1>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-neutral-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-semibold text-black">{{ $stats['users'] }}</div>
            <span class="text-xs text-green-600">+{{ $growth['users'] }} за 7д</span>
        </div>
        <div class="text-xs text-neutral-500 mt-1">пользователей</div>
    </div>
    <div class="bg-white border border-neutral-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-semibold text-black">{{ $stats['topics'] }}</div>
            <span class="text-xs text-green-600">+{{ $growth['topics'] }} за 7д</span>
        </div>
        <div class="text-xs text-neutral-500 mt-1">тем</div>
    </div>
    <div class="bg-white border border-neutral-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-semibold text-black">{{ $stats['posts'] }}</div>
            <span class="text-xs text-green-600">+{{ $growth['posts'] }} за 7д</span>
        </div>
        <div class="text-xs text-neutral-500 mt-1">сообщений</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white border border-neutral-200 rounded-lg p-4">
        <div class="text-sm font-medium text-black mb-3">Регистрации за 14 дней</div>
        <div class="h-56">
            <canvas id="registrationsChart" data-labels="{{ $chartLabels->toJson() }}" data-values="{{ $registrationsChart->toJson() }}"></canvas>
        </div>
    </div>
    <div class="bg-white border border-neutral-200 rounded-lg p-4">
        <div class="text-sm font-medium text-black mb-3">Сообщения за 14 дней</div>
        <div class="h-56">
            <canvas id="postsChart" data-labels="{{ $chartLabels->toJson() }}" data-values="{{ $postsChart->toJson() }}"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white border border-neutral-200 rounded-lg p-4">
        <div class="text-sm font-medium text-black mb-3">Темы по разделам</div>
        <div class="h-56">
            <canvas id="categoriesChart" data-labels="{{ $categoryStats->pluck('name')->toJson() }}" data-values="{{ $categoryStats->pluck('topics_count')->toJson() }}"></canvas>
        </div>
    </div>
    <div class="bg-white border border-neutral-200 rounded-lg p-4">
        <div class="text-sm font-medium text-black mb-3">Пользователи по ролям</div>
        <div class="h-56 flex items-center justify-center">
            <canvas id="rolesChart" data-labels="{{ $roleStats->keys()->toJson() }}" data-values="{{ $roleStats->values()->toJson() }}"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
        <div class="text-sm font-medium text-black px-4 py-3 border-b border-neutral-200">Топ активных пользователей</div>
        @foreach ($topUsers as $user)
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-neutral-200 last:border-b-0 text-sm">
                <span class="text-black">{{ $user->name }}</span>
                <span class="text-neutral-400">{{ $user->posts_count }} сообщений</span>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
        <div class="text-sm font-medium text-black px-4 py-3 border-b border-neutral-200">Последняя активность</div>
        @foreach ($recentActivity as $item)
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-neutral-200 last:border-b-0 text-sm gap-3">
                @if ($item['url'])
                    <a href="{{ $item['url'] }}" class="text-black hover:underline truncate">{{ $item['text'] }}</a>
                @else
                    <span class="text-black truncate">{{ $item['text'] }}</span>
                @endif
                <span class="text-neutral-400 text-xs whitespace-nowrap">{{ $item['created_at']->diffForHumans() }}</span>
            </div>
        @endforeach
    </div>
</div>

<div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
    <div class="text-sm font-medium text-black px-4 py-3 border-b border-neutral-200">Разделы подробно</div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs uppercase text-neutral-400 border-b border-neutral-200">
                <th class="px-4 py-2 font-medium">Раздел</th>
                <th class="px-4 py-2 font-medium">Тем</th>
                <th class="px-4 py-2 font-medium">Сообщений</th>
                <th class="px-4 py-2 font-medium">Последняя активность</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categoryStats as $category)
                <tr class="border-b border-neutral-200 last:border-b-0">
                    <td class="px-4 py-2.5 text-black">{{ $category->name }}</td>
                    <td class="px-4 py-2.5 text-neutral-600">{{ $category->topics_count }}</td>
                    <td class="px-4 py-2.5 text-neutral-600">{{ $category->posts_count }}</td>
                    <td class="px-4 py-2.5 text-neutral-400">{{ $category->last_activity ? \Illuminate\Support\Carbon::parse($category->last_activity)->diffForHumans() : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const labels = @json($chartLabels);
    const baseOptions = { responsive: true, maintainAspectRatio: false };

    new Chart(document.getElementById('registrationsChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Регистрации',
                data: @json($registrationsChart),
                borderColor: '#000000',
                backgroundColor: 'rgba(0,0,0,0.05)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });

    new Chart(document.getElementById('postsChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Сообщения',
                data: @json($postsChart),
                borderColor: '#000000',
                backgroundColor: 'rgba(0,0,0,0.05)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });

    new Chart(document.getElementById('categoriesChart'), {
        type: 'bar',
        data: {
            labels: @json($categoryStats->pluck('name')),
            datasets: [{
                label: 'Тем',
                data: @json($categoryStats->pluck('topics_count')),
                backgroundColor: '#000000',
            }]
        },
        options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });

    new Chart(document.getElementById('rolesChart'), {
        type: 'doughnut',
        data: {
            labels: @json($roleStats->keys()),
            datasets: [{
                data: @json($roleStats->values()),
                backgroundColor: ['#000000', '#737373', '#a3a3a3', '#d4d4d4'],
            }]
        },
        options: baseOptions
    });
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold text-white mb-6">Разделы форума</h1>

    @foreach ($categories as $category)
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-5 hover:border-slate-700 transition">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-medium text-white">{{ $category->name }}</h2>
                    <p class="text-sm text-slate-400 mt-1">{{ $category->description }}</p>
                </div>
                <span class="text-sm text-slate-500">{{ $category->topics_count }} тем</span>
            </div>
        </div>
    @endforeach
</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $relations = ['user', 'category', 'posts' => fn ($q) => $q->latest()->limit(1)->with('user')];

        $pinnedTopics = $category->topics()
            ->withCount('posts')
            ->with($relations)
            ->withMax('posts', 'created_at')
            ->where('is_pinned', true)
            ->latest()
            ->get();

        $topics = $category->topics()
            ->withCount('posts')
            ->with($relations)
            ->withMax('posts', 'created_at')
            ->where('is_pinned', false)
            ->latest()
            ->paginate(15);

        return view('categories.show', compact('category', 'pinnedTopics', 'topics'));
    }
}

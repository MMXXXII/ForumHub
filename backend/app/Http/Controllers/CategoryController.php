<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $topics = $category->topics()
            ->withCount('posts')
            ->with('user')
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('categories.show', compact('category', 'topics'));
    }
}
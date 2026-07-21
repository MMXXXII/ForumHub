<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function show(Topic $topic)
    {
        $sessionKey = 'viewed_topic_'.$topic->id;
        if (! session()->has($sessionKey)) {
            $topic->increment('views');
            session()->put($sessionKey, true);
        }

        $topic->load(['category', 'user']);

        $query = $topic->posts()->with('user');

        if (auth()->check() && auth()->user()->isModerator()) {
            $query->whereIn('moderation_status', ['approved', 'rejected', 'pending']);
        } else {
            $userId = auth()->id();
            $query->where(function ($q) use ($userId) {
                $q->where('moderation_status', 'approved');
                if ($userId) {
                    $q->orWhere('user_id', $userId);
                }
            });
        }

        $all = $query->orderBy('created_at')->get();

        $grouped = $all->groupBy('parent_id');
        $visibleIds = $all->pluck('id')->flip();

        $attach = function ($post) use (&$attach, $grouped) {
            $children = $grouped->get($post->id, collect())->map($attach)->values();
            $post->setRelation('childrenPosts', $children);

            return $post;
        };

        $rootPosts = $all->filter(function ($p) use ($visibleIds) {
            return is_null($p->parent_id) || ! $visibleIds->has($p->parent_id);
        })->map($attach)->values();

        return view('topics.show', compact('topic', 'rootPosts'));
    }

    public function destroy(Request $request, Topic $topic)
    {
        $user = $request->user();

        if ($user->id !== $topic->user_id && ! $user->isModerator()) {
            abort(403);
        }

        $category = $topic->category;
        $topic->posts()->delete();
        $topic->delete();

        return redirect()->route('categories.show', $category)->with('status', 'Тема удалена.');
    }

    public function create()
    {
        $categories = Category::orderBy('order')->get();

        return view('topics.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'body' => ['required', 'string', 'min:2', 'max:10000'],
        ]);

        $base = Str::slug($validated['title']);
        if ($base === '') {
            $base = 'topic';
        }

        $topic = Topic::create([
            'category_id' => $validated['category_id'],
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $base,
        ]);

        $topic->update(['slug' => $topic->id.'-'.$base]);

        $topic->posts()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return redirect()->route('topics.show', $topic)->with('status', 'Тема создана.');
    }
}

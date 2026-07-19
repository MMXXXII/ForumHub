<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'topics' => Topic::count(),
            'posts' => Post::count(),
        ];

        $growth = [
            'users' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'topics' => Topic::where('created_at', '>=', now()->subDays(7))->count(),
            'posts' => Post::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->toDateString());

        $registrationsRaw = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('date')
            ->pluck('count', 'date');

        $postsRaw = Post::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('date')
            ->pluck('count', 'date');

        $chartLabels = $days->map(fn ($date) => Carbon::parse($date)->format('d.m'));
        $registrationsChart = $days->map(fn ($date) => (int) ($registrationsRaw[$date] ?? 0));
        $postsChart = $days->map(fn ($date) => (int) ($postsRaw[$date] ?? 0));

        $categoryStats = Category::withCount('topics')->orderBy('order')->get();
        $categoryStats->each(function (Category $category) {
            $category->posts_count = Post::whereHas('topic', fn ($q) => $q->where('category_id', $category->id))->count();
            $category->last_activity = Topic::where('category_id', $category->id)->max('created_at');
        });

        $roleStats = User::selectRaw('role, COUNT(*) as count')->groupBy('role')->pluck('count', 'role');

        $topUsers = User::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        $recentTopics = Topic::with(['user', 'category'])->latest()->take(5)->get()->map(fn ($t) => [
            'text' => "{$t->user->name} создал тему «{$t->title}»",
            'created_at' => $t->created_at,
            'url' => route('topics.show', $t),
        ]);

        $recentPosts = Post::with(['user', 'topic'])->latest()->take(5)->get()->map(fn ($p) => [
            'text' => "{$p->user->name} ответил в «{$p->topic->title}»",
            'created_at' => $p->created_at,
            'url' => route('topics.show', $p->topic),
        ]);

        $recentUsers = User::latest()->take(5)->get()->map(fn ($u) => [
            'text' => "{$u->name} зарегистрировался",
            'created_at' => $u->created_at,
            'url' => null,
        ]);

        $recentActivity = $recentTopics->concat($recentPosts)->concat($recentUsers)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        $moderationStats = [
            'approved' => Post::where('moderation_status', 'approved')->count(),
            'rejected' => Post::where('moderation_status', 'rejected')->count(),
            'pending' => Post::where('moderation_status', 'pending')->count(),
        ];

        $moderationStats['total'] = array_sum($moderationStats);
        $moderationStats['blocked_rate'] = $moderationStats['total'] > 0
            ? round($moderationStats['rejected'] / $moderationStats['total'] * 100, 1)
            : 0;

        $moderationStats['avg_score'] = round((float) Post::where('moderation_status', 'rejected')->avg('confidence_score') * 100, 1);

        $rejectedRaw = Post::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('moderation_status', 'rejected')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('date')
            ->pluck('count', 'date');

        $rejectedChart = $days->map(fn ($date) => (int) ($rejectedRaw[$date] ?? 0));

        $recentRejected = Post::with(['user', 'topic'])
            ->where('moderation_status', 'rejected')
            ->orderByDesc('confidence_score')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'growth', 'chartLabels', 'registrationsChart', 'postsChart',
            'categoryStats', 'roleStats', 'topUsers', 'recentActivity',
            'moderationStats', 'rejectedChart', 'recentRejected'
        ));
    }
}
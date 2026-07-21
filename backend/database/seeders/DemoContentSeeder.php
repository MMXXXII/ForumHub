<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'demo_user',
            'email' => 'demo@forumhub.local',
        ]);

        Category::all()->each(function (Category $category) use ($user) {
            for ($i = 1; $i <= 3; $i++) {
                $topic = Topic::create([
                    'category_id' => $category->id,
                    'user_id' => $user->id,
                    'title' => "{$category->name}: тестовая тема {$i}",
                    'slug' => "{$category->slug}-topic-{$i}",
                ]);

                for ($j = 1; $j <= 5; $j++) {
                    Post::create([
                        'topic_id' => $topic->id,
                        'user_id' => $user->id,
                        'body' => "Тестовое сообщение №{$j} в теме «{$topic->title}».",
                        'moderation_status' => 'approved',
                    ]);
                }
            }
        });
    }
}

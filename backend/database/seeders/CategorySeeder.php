<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Общие обсуждения', 'slug' => 'general', 'description' => 'Обсуждения на свободные темы'],
            ['name' => 'Технологии', 'slug' => 'tech', 'description' => 'IT, программирование, новости технологий'],
            ['name' => 'Помощь новичкам', 'slug' => 'help', 'description' => 'Вопросы и ответы для новых участников'],
        ];

        foreach ($categories as $index => $category) {
            Category::create([...$category, 'order' => $index]);
        }
    }
}
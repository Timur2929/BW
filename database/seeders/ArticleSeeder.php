<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Колье',
                'content' => 'Откуда взялось колье? каково его происхождение?',
                'image' => 'images/articles/col.jpg',
                'status' => 'published',
            ],
            [
                'title' => 'Серьги',
                'content' => 'Удивительная история возникновения этого украшения',
                'image' => 'images/articles/sergi.jpg',
                'status' => 'published',
            ],
            [
                'title' => 'Браслет',
                'content' => 'Все началось в древней греции...',
                'image' => 'images/articles/brasleti.jpg',
                'status' => 'published',
            ],
        ];

        $userId = \App\Models\User::first()->id;
        foreach ($articles as $article) {
            Article::create([
                'title' => $article['title'],
                'content' => $article['content'],
                'image' => $article['image'],
                'status' => $article['status'],
                'user_id' => $userId,
            ]);
        }
    }
} 
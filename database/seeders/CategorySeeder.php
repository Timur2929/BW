<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Колье', 'image' => 'images/categories/Kamenika.jpg'],
            ['name' => 'Серьги', 'image' => 'images/categories/Kamenika.jpg'],
            ['name' => 'Браслеты', 'image' => 'images/categories/Kamenika.jpg'],
            ['name' => 'Сувениры', 'image' => 'images/categories/Kamenika.jpg'],
            ['name' => 'Чокеры', 'image' => 'images/categories/Kamenika.jpg'],
            ['name' => 'Брошки', 'image' => 'images/categories/Kamenika.jpg'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 
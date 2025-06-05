<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Мясные блюда', 'image' => 'images/categories/meat.png'],
            ['name' => 'Супы', 'image' => 'images/categories/soups.png'],
            ['name' => 'Салаты', 'image' => 'images/categories/salads.png'],
            ['name' => 'Десерты', 'image' => 'images/categories/desserts.png'],
            ['name' => 'Выпечка', 'image' => 'images/categories/bakery.png'],
            ['name' => 'Напитки', 'image' => 'images/categories/drinks.png'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 
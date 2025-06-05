<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
          
                 [
        'name' => 'Гуляш из говядины',
        'category_name' => 'Мясные блюда',
        'description' => 'Ароматное мясное рагу с подливкой, идеально подходит к гарнирам.',
        'cooking_time' => '1,5 часа',
        'image' => 'images/recipes/gulash.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Говядина', 'amount' => '600', 'unit' => 'г'],
            ['name' => 'Лук', 'amount' => '2', 'unit' => 'шт'],
            ['name' => 'Мука', 'amount' => '1', 'unit' => 'ст.л'],
            ['name' => 'Томатная паста', 'amount' => '2', 'unit' => 'ст.л']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Обжарить мясо до румяной корочки'],
            ['order' => 2, 'description' => 'Добавить лук и муку'],
            ['order' => 3, 'description' => 'Тушить с томатной пастой 1 час']
        ]
    ],
    [
        'name' => 'Куриный суп с лапшой',
        'category_name' => 'Супы',
        'description' => 'Лёгкий прозрачный суп с домашней лапшой.',
        'cooking_time' => '45 минут',
        'image' => 'images/recipes/lapsha.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Курица', 'amount' => '500', 'unit' => 'г'],
            ['name' => 'Лапша', 'amount' => '150', 'unit' => 'г'],
            ['name' => 'Морковь', 'amount' => '1', 'unit' => 'шт']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Сварить куриный бульон'],
            ['order' => 2, 'description' => 'Добавить овощи и лапшу'],
            ['order' => 3, 'description' => 'Варить 10 минут']
        ]
    ],
    [
        'name' => 'Оливье',
        'category_name' => 'Салаты',
        'description' => 'Классический праздничный салат с колбасой.',
        'cooking_time' => '30 минут',
        'image' => 'images/recipes/oleve.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Колбаса варёная', 'amount' => '300', 'unit' => 'г'],
            ['name' => 'Картофель', 'amount' => '3', 'unit' => 'шт'],
            ['name' => 'Майонез', 'amount' => '100', 'unit' => 'г']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Отварить овощи'],
            ['order' => 2, 'description' => 'Нарезать кубиками'],
            ['order' => 3, 'description' => 'Заправить майонезом']
        ]
    ],
    [
        'name' => 'Тирамису',
        'category_name' => 'Десерты',
        'description' => 'Итальянский десерт с кофейным вкусом.',
        'cooking_time' => '2 часа',
        'image' => 'images/recipes/tiramis.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Сыр маскарпоне', 'amount' => '500', 'unit' => 'г'],
            ['name' => 'Печенье савоярди', 'amount' => '200', 'unit' => 'г'],
            ['name' => 'Кофе', 'amount' => '100', 'unit' => 'мл']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Приготовить крем'],
            ['order' => 2, 'description' => 'Собрать слои'],
            ['order' => 3, 'description' => 'Охладить 2 часа']
        ]
    ],
    [
        'name' => 'Блины',
        'category_name' => 'Выпечка',
        'description' => 'Тонкие блины на молоке.',
        'cooking_time' => '30 минут',
        'image' => 'images/recipes/blene.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Молоко', 'amount' => '500', 'unit' => 'мл'],
            ['name' => 'Яйца', 'amount' => '2', 'unit' => 'шт'],
            ['name' => 'Мука', 'amount' => '200', 'unit' => 'г']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Смешать ингредиенты'],
            ['order' => 2, 'description' => 'Жарить на сковороде'],
            ['order' => 3, 'description' => 'Подавать с начинкой']
        ]
    ],
    [
        'name' => 'Мохито',
        'category_name' => 'Напитки',
        'description' => 'Освежающий коктейль с мятой и лаймом.',
        'cooking_time' => '5 минут',
        'image' => 'images/recipes/moxito.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Содовая', 'amount' => '100', 'unit' => 'мл'],
            ['name' => 'Лайм', 'amount' => '1', 'unit' => 'шт'],
            ['name' => 'Мята', 'amount' => '5', 'unit' => 'листьев']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Размять мяту с лаймом'],
            ['order' => 2, 'description' => 'Добавить лёд и содовую'],
            ['order' => 3, 'description' => 'Перемешать']
        ]
    ],
    [
        'name' => 'Жаркое из свинины',
        'category_name' => 'Мясные блюда',
        'description' => 'Свинина, тушёная с картофелем.',
        'cooking_time' => '1 час',
        'image' => 'images/recipes/jarkoe.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Свинина', 'amount' => '600', 'unit' => 'г'],
            ['name' => 'Картофель', 'amount' => '5', 'unit' => 'шт'],
            ['name' => 'Лук', 'amount' => '2', 'unit' => 'шт']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Обжарить мясо'],
            ['order' => 2, 'description' => 'Добавить овощи'],
            ['order' => 3, 'description' => 'Тушить 40 минут']
        ]
    ],
    [
        'name' => 'Греческий салат',
        'category_name' => 'Салаты',
        'description' => 'Свежий салат с фетаксой и оливками.',
        'cooking_time' => '15 минут',
        'image' => 'images/recipes/grecheski.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Огурцы', 'amount' => '2', 'unit' => 'шт'],
            ['name' => 'Помидоры', 'amount' => '2', 'unit' => 'шт'],
            ['name' => 'Сыр фета', 'amount' => '100', 'unit' => 'г']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Нарезать овощи'],
            ['order' => 2, 'description' => 'Добавить сыр'],
            ['order' => 3, 'description' => 'Заправить оливковым маслом']
        ]
    ],
    [
        'name' => 'Шарлотка',
        'category_name' => 'Выпечка',
        'description' => 'Яблочный пирог на бисквитном тесте.',
        'cooking_time' => '50 минут',
        'image' => 'images/recipes/sharla.png',
        'status' => Recipe::STATUS_APPROVED,
        'ingredients' => [
            ['name' => 'Яблоки', 'amount' => '3', 'unit' => 'шт'],
            ['name' => 'Яйца', 'amount' => '4', 'unit' => 'шт'],
            ['name' => 'Мука', 'amount' => '200', 'unit' => 'г']
        ],
        'steps' => [
            ['order' => 1, 'description' => 'Взбить яйца с сахаром'],
            ['order' => 2, 'description' => 'Добавить муку и яблоки'],
            ['order' => 3, 'description' => 'Выпекать 40 минут']
        ]
        ],




        ];

        $user = User::first();

        foreach ($recipes as $recipeData) {
            $category = Category::where('name', $recipeData['category_name'])->first();
            
            if ($user && $category) {
                $recipe = Recipe::create([
                    'name' => $recipeData['name'],
                    'category_id' => $category->id,
                    'description' => $recipeData['description'],
                    'cooking_time' => $recipeData['cooking_time'],
                    'image' => $recipeData['image'],
                    'user_id' => $user->id,
                    'status' => $recipeData['status'],
                    'rating' => 0,
                    'views' => 0
                ]);

                // Добавляем ингредиенты
                foreach ($recipeData['ingredients'] as $ingredient) {
                    $recipe->ingredients()->create($ingredient);
                }

                // Добавляем шаги
                foreach ($recipeData['steps'] as $step) {
                    $recipe->steps()->create($step);
                }
            }
        }
    }
} 
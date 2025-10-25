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
                'name' => 'Сумерки',
                'category_name' => 'Колье',
                'description' => 'Невероятное украшение для вашей любимой!',
                'cooking_time' => '2 дня',
                'image' => 'images/recipes/sumerki.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 2500.00, // цена в рублях
                'quantity' => 12, // количество в наличии
                'ingredients' => [
                    ['name' => 'Кахалонг', 'amount' => '1', 'unit' => 'шт'],
                    ['name' => 'Ювелирная цепочка', 'amount' => '2', 'unit' => 'шт'],
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            [
                'name' => 'Серьги с аметистом',
                'category_name' => 'Серьги',
                'description' => 'Очень крутое украшение.',
                'cooking_time' => '1 день',
                'image' => 'images/recipes/ame.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 3000.00,
                'quantity' => 8,
                'ingredients' => [
                    ['name' => 'Латунь', 'amount' => '3', 'unit' => 'шт'],
                    ['name' => 'Аметист', 'amount' => '1', 'unit' => 'шт'],
                    ['name' => 'Ювелирная цепь', 'amount' => '2', 'unit' => 'шт']
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            [
                'name' => 'Ловец добрых духов',
                'category_name' => 'Сувениры',
                'description' => 'Древнейшее украшение в вашем доме!',
                'cooking_time' => '1 день',
                'image' => 'images/recipes/dobr.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 3200.00,
                'quantity' => 6,
                'ingredients' => [
                    ['name' => 'Нефрит', 'amount' => '1', 'unit' => 'шт'],
                    ['name' => 'бусины позолоченные', 'amount' => '5', 'unit' => 'шт'],
                    ['name' => 'перья позолоченные', 'amount' => '5', 'unit' => 'шт']
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            // [
            //     'name' => 'Тирамису',
            //     'category_name' => 'Браслеты',
            //     'description' => 'Итальянский десерт с кофейным вкусом.',
            //     'cooking_time' => '2 часа',
            //     'image' => 'images/recipes/tiramis.png',
            //     'status' => Recipe::STATUS_APPROVED,
            //     'price' => 1500,
            //     'quantity' => 8,
            //     'ingredients' => [
            //         ['name' => 'Сыр маскарпоне', 'amount' => '500', 'unit' => 'г'],
            //         ['name' => 'Печенье савоярди', 'amount' => '200', 'unit' => 'г'],
            //         ['name' => 'Кофе', 'amount' => '100', 'unit' => 'мл']
            //     ],
            //     'steps' => [
            //         ['order' => 1, 'description' => 'Приготовить крем'],
            //         ['order' => 2, 'description' => 'Собрать слои'],
            //         ['order' => 3, 'description' => 'Охладить 2 часа']
            //     ]
            // ],
            // Остальные рецепты...
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
                    'price' => $recipeData['price'], // добавляем цену
                    'quantity' => $recipeData['quantity'], // добавляем количество
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
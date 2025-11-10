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
                'name' => 'Колье "Бронзовое солнце"',
                'category_name' => 'Колье',
                'description' => 'Невероятное украшение для вашей любимой!',
                'cooking_time' => '2 дня',
                'image' => 'images/recipes/sumerki2.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 5350.00, // цена в рублях
                'quantity' => 2, // количество в наличии
                'ingredients' => [
                    ['name' => 'Цитрин', 'amount' => '19', 'unit' => 'шт'],
                    ['name' => 'фурнитура Anna Bronze', 'amount' => '1', 'unit' => 'шт']
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
                'quantity' => 3,
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
                'name' => 'Серьги "Жемчужный сказ"',
                'category_name' => 'Серьги',
                'description' => 'Очень крутое украшение.',
                'cooking_time' => '1 день',
                'image' => 'images/recipes/ame2.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 2600.00,
                'quantity' => 2,
                'ingredients' => [
                    ['name' => 'Пресноводный жемчуг', 'amount' => '3', 'unit' => 'шт'],
                    ['name' => 'Барочный жемчуг', 'amount' => '4', 'unit' => 'шт'],
                    ['name' => 'Фурнитура из латуни', 'amount' => '4', 'unit' => 'шт'],
                    ['name' => 'Покрытие из серебра', 'amount' => '4', 'unit' => 'шт']
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
                'quantity' => 2,
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
            [
                'name' => 'Ловец добрых духов (Кытгьем)',
                'category_name' => 'Сувениры',
                'description' => 'Древнейшее украшение в вашем доме!',
                'cooking_time' => '1 день',
                'image' => 'images/recipes/dobr2.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 2600.00,
                'quantity' => 1,
                'ingredients' => [
                    ['name' => 'Аметист', 'amount' => '1', 'unit' => 'шт'],
                    ['name' => 'Агат', 'amount' => '5', 'unit' => 'шт'],
                    ['name' => 'Родированные бусы', 'amount' => '5', 'unit' => 'шт'],
                    ['name' => 'Родированные перья', 'amount' => '5', 'unit' => 'шт']         
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            
            [
                'name' => 'Браслет "Богиня ночи"',
                'category_name' => 'Браслеты',
                'description' => 'Невероятное укаршение для вашей дамы!',
                'cooking_time' => '3 дня',
                'image' => 'images/recipes/bogi.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 2500.00,
                'quantity' => 5,
                'ingredients' => [
                    ['name' => 'Лазурит', 'amount' => '1', 'unit' => 'шт'],
                    ['name' => 'Золото', 'amount' => '5', 'unit' => 'шт']
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            [
                'name' => 'Браслет "Чары Сибири"',
                'category_name' => 'Браслеты',
                'description' => 'Невероятное укаршение для вашей дамы!',
                'cooking_time' => '1 день',
                'image' => 'images/recipes/bogi2.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 2500.00,
                'quantity' => 2,
                'ingredients' => [
                    ['name' => 'Чароит', 'amount' => '1', 'unit' => 'шт'],
                    ['name' => 'Латунь с покрытием - родий', 'amount' => '1', 'unit' => 'шт'],
                    ['name' => 'Вулканическая лава', 'amount' => '5', 'unit' => 'шт']
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            [
                'name' => 'Чокер "Орлинное гнездо"',
                'category_name' => 'Чокеры',
                'description' => 'Невероятное укаршение для вашей дамы!',
                'cooking_time' => '2 дня',
                'image' => 'images/recipes/orli.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 4000.00,
                'quantity' => 5,
                'ingredients' => [
                    ['name' => 'Родонит', 'amount' => '15', 'unit' => 'шт'],
                    ['name' => 'Цепочка из Золота', 'amount' => '5', 'unit' => 'шт']
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            [
                'name' => 'Чокер "Нефритовый цветок"',
                'category_name' => 'Чокеры',
                'description' => 'Невероятное укаршение для вашей дамы!',
                'cooking_time' => '2 дня',
                'image' => 'images/recipes/orli2.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 4450.00,
                'quantity' => 2,
                'ingredients' => [
                    ['name' => 'Байкальский нефрит', 'amount' => '5', 'unit' => 'шт'],
                    ['name' => 'Гематит', 'amount' => '8', 'unit' => 'шт'],
                    ['name' => 'Покрытие - родий', 'amount' => '1', 'unit' => 'шт'],
                    ['name' => 'Цепочка из Золота', 'amount' => '5', 'unit' => 'шт']
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            [
                'name' => 'Брошь булавка с чароитом',
                'category_name' => 'Брошки',
                'description' => 'Невероятное укаршение для вашей дамы!',
                'cooking_time' => '1 день',
                'image' => 'images/recipes/chari.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 2000.00,
                'quantity' => 5,
                'ingredients' => [
                    ['name' => 'Чароит', 'amount' => '10', 'unit' => 'шт'],
                    ['name' => 'Латунь', 'amount' => '5', 'unit' => 'шт'],
                    ['name' => 'Покрытие из родия', 'amount' => '5', 'unit' => 'шт']
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
                ]
            ],
            [
                'name' => 'Брошь "Байкал и Ангара"',
                'category_name' => 'Брошки',
                'description' => 'Невероятное укаршение для вашей дамы!',
                'cooking_time' => '1 день',
                'image' => 'images/recipes/chari2.jpg',
                'status' => Recipe::STATUS_APPROVED,
                'price' => 2000.00,
                'quantity' => 5,
                'ingredients' => [
                    ['name' => 'Аквамарин', 'amount' => '10', 'unit' => 'шт'],
                    ['name' => 'Содалит', 'amount' => '5', 'unit' => 'шт'],
                    ['name' => 'Лазурит', 'amount' => '5', 'unit' => 'шт'],
                    ['name' => 'Аквакарц', 'amount' => '5', 'unit' => 'шт'],
                    ['name' => 'Инкрустация Фианитами', 'amount' => '5', 'unit' => 'шт']
                ],
                'steps' => [
                    ['order' => 1, 'description' => 'Фирменная упаковка в подарок'],
                    ['order' => 2, 'description' => 'Доставка бесплатно'],
                    ['order' => 3, 'description' => 'Ювелирная салфетка и памятка по уходу']
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
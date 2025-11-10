<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = \App\Models\Recipe::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'ingredients' => $this->faker->text(),
            'instructions' => $this->faker->text(),
            'cooking_time' => $this->faker->numberBetween(10, 120),
            'servings' => $this->faker->numberBetween(1, 10),
            'difficulty' => 'easy',
            'price' => $this->faker->randomFloat(2, 5, 50),
            'quantity' => 1,
        ];
    }
}
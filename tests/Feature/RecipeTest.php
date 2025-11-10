<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем тестовую категорию
        $this->category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'image' => 'test.jpg',
        ]);
    }

    /** @test */
    public function it_can_create_a_recipe()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'phone' => '+1234567890',
        ]);

        $recipe = Recipe::create([
            'name' => 'Test Recipe',
            'category_id' => $this->category->id,
            'description' => 'Test Description',
            'cooking_time' => '30 минут',
            'image' => 'test.jpg',
            'user_id' => $user->id,
            'price' => 100.50,
            'quantity' => 5,
        ]);

        $this->assertDatabaseHas('recipes', [
            'name' => 'Test Recipe',
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_can_update_recipe_status()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'phone' => '+1234567890',
        ]);

        $recipe = Recipe::create([
            'name' => 'Test Recipe',
            'category_id' => $this->category->id,
            'description' => 'Test Description',
            'cooking_time' => '30 минут',
            'image' => 'test.jpg',
            'user_id' => $user->id,
            'price' => 100.50,
            'quantity' => 5,
        ]);

        $recipe->update(['status' => 'approved']);

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'status' => 'approved',
        ]);
    }
}
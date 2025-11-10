<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationshipsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'image' => 'test.jpg',
        ]);
    }

    /** @test */
    public function user_can_have_recipes()
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

        $this->assertEquals($user->id, $recipe->user_id);
        $this->assertCount(1, $user->recipes);
    }

    /** @test */
    public function user_can_have_reviews()
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

        $review = Review::create([
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'rating' => 5,
            'comment' => 'Great recipe!',
        ]);

        $this->assertEquals($user->id, $review->user_id);
        $this->assertCount(1, $user->reviews);
    }

    /** @test */
    public function recipe_can_have_reviews()
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

        $review = Review::create([
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'rating' => 5,
            'comment' => 'Great recipe!',
        ]);

        $this->assertEquals($recipe->id, $review->recipe_id);
        $this->assertCount(1, $recipe->reviews);
    }

    /** @test */
    public function recipe_belongs_to_category()
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

        $this->assertEquals($this->category->id, $recipe->category_id);
        $this->assertInstanceOf(Category::class, $recipe->category);
    }
}
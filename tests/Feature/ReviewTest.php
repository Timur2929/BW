<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
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
    public function it_can_create_a_review()
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

        $this->assertDatabaseHas('reviews', [
            'rating' => 5,
            'comment' => 'Great recipe!',
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }
}
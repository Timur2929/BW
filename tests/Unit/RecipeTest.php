<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Recipe;

class RecipeTest extends TestCase
{
    /** @test */
    public function it_can_create_recipe()
    {
        $recipe = new Recipe([
            'name' => 'Test Recipe',
            'description' => 'Test Description',
            'cooking_time' => 30,
            'servings' => 4,
            'difficulty' => '',
            'price' => 15.99,
            'quantity' => 1,
        ]);

        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertEquals('Test Recipe', $recipe->name);
        $this->assertEquals(15.99, $recipe->price);
    }

    /** @test */
    public function it_has_default_status_pending()
    {
        $recipe = new Recipe();
        $this->assertEquals(Recipe::STATUS_PENDING, $recipe->status);
    }

    /** @test */
    public function it_can_check_statuses()
    {
        $recipe = new Recipe(['status' => Recipe::STATUS_PENDING]);
        $this->assertTrue($recipe->isPending());

        $recipe = new Recipe(['status' => Recipe::STATUS_APPROVED]);
        $this->assertTrue($recipe->isApproved());

        $recipe = new Recipe(['status' => Recipe::STATUS_REJECTED]);
        $this->assertTrue($recipe->isRejected());
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $recipe = new Recipe();
        $fillable = $recipe->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('price', $fillable);
        $this->assertContains('quantity', $fillable);
    }
}
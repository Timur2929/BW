<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Review;

class ReviewTest extends TestCase
{
    /** @test */
    public function it_can_create_review()
    {
        $review = new Review([
            'rating' => 5,
            'comment' => 'Excellent recipe!',
        ]);

        $this->assertInstanceOf(Review::class, $review);
        $this->assertEquals(5, $review->rating);
        $this->assertEquals('Excellent recipe!', $review->comment);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $review = new Review();
        $fillable = $review->getFillable();

        $this->assertContains('rating', $fillable);
        $this->assertContains('comment', $fillable);
        $this->assertContains('user_id', $fillable);
        $this->assertContains('recipe_id', $fillable);
    }

    /** @test */
    public function it_casts_rating_to_integer()
    {
        $review = new Review(['rating' => '5']);
        $this->assertIsInt($review->rating);
    }
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'phone' => '+1234567890',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'role' => 'user',
        ]);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'phone' => '+1234567890',
        ]);

        $user->update(['first_name' => 'Jane']);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'first_name' => 'Jane',
        ]);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'phone' => '+1234567890',
        ]);

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'email' => 'john@example.com',
        ]);
    }
}
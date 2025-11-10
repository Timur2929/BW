<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /** @test */
    public function it_can_create_user()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'avatar' => 'images/profile/default-avatar.png',
            'cover_image' => 'images/profile/default-cover.jpg',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertEquals('john@example.com', $user->email);
    }

    /** @test */
    public function it_returns_full_name()
    {
        $user = new User([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $user->full_name);
    }

    /** @test */
    public function it_has_default_role_user()
    {
        $user = new User();
        $this->assertEquals(User::ROLE_USER, $user->role);
    }

    /** @test */
    public function it_can_check_admin_role()
    {
        $user = new User(['role' => User::ROLE_ADMIN]);
        $this->assertTrue($user->isAdmin());

        $user = new User(['role' => User::ROLE_USER]);
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function it_can_check_user_role()
    {
        $user = new User(['role' => User::ROLE_USER]);
        $this->assertTrue($user->isUser());

        $user = new User(['role' => User::ROLE_ADMIN]);
        $this->assertFalse($user->isUser());
    }
}
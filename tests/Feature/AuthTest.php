<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test
     *  */
    public function user_can_register()
    {
        //Arrange
        $userName = 'John Doe';
        $userEmail = 'john.doe@example.com';
        $userPassword = 'password123';

        //Act
        $response = $this->postJson('/api/register', [
            'name' => $userName,
            'email' => $userEmail,
            'password' => $userPassword,
        ]);

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function user_can_login()
    {
        //Arrange
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);
        $userEmail = 'john.doe@example.com';
        $userPassword = 'password123';

        //Act
        $response = $this->postJson('/api/login', [
            'email' => $userEmail,
            'password' => $userPassword,
        ]);

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }
}

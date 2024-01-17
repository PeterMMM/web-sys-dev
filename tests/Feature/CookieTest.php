<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class CookieTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_access_cookies_route()
    {
        //Arrange
        // Assuming you have a user in the database
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);
        
        // dump("User ID: {$user->id}, Email: {$user->email}, User: {$user}");

        //Act
        $response = $this->postJson('/api/login', [
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);
        $token = $response['token'];

        // dump("Token: {$token}");

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->get('/api/cookies');
        
        // dump("Response Content: {$response->content()}");
        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'status', 'top3Cookies', 'cookies']);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_cookies_route()
    {
        $response = $this->get('/api/cookies');
        // dump("Response Content: {$response->content()}");

        $response->assertStatus(401);
    }
}

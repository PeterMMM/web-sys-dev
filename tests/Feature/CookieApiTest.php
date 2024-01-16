<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CookieApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_access_test_cookies_route()
    {
        $response = $this->get('/api/test_cookies');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'status', 'top3Cookies', 'cookies']);
    }
}

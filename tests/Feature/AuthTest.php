<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_signup()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertOk();
    }

    public function test_users_can_authenticate()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertJsonStructure([
            'user' => [
                'name',
                'email',
            ],
            'token'
        ])
            ->assertJsonPath('user.email', $user->email);
    }

    public function test_user_can_refresh_token()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->actingAs($user)
            ->get('/refresh-token?token='.$token);

        $response->assertOk();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->actingAs($user)
            ->post('/logout?token='.$token);

        $response->assertOk();
    }
}

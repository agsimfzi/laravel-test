<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_find_all(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_find_one(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/users/'.$user->id);

        $response->assertOk();
    }
}

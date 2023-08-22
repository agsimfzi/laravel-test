<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_create(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/products', [
            'name' => $this->faker->name(),
            'price' => $this->faker->numberBetween(1, 1000000),
            'stock' => $this->faker->numberBetween(1, 100),
            'weight' => $this->faker->numberBetween(1, 50),
        ]);

        $response->assertCreated();
    }

    public function test_user_can_find_all(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);
    }

    public function test_find_one(): void
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->get('/products/' . $product->id);

        $response->assertOk();
    }

    public function test_user_can_update(): void
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->put('/products/' . $product->id, [
                'name' => $this->faker->name(),
                'price' => $this->faker->numberBetween(1, 1000000),
                'stock' => $this->faker->numberBetween(1, 100),
                'weight' => $this->faker->numberBetween(1, 50),
            ]);

        $response->assertNoContent();
    }

    public function test_user_can_remove(): void
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        $response = $this->actingAs($user)
            ->delete('/products/' . $product->id);

        $response->assertNoContent();
    }
}

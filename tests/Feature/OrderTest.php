<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can view their orders
     */
    public function test_authenticated_user_can_view_their_orders()
    {
        $user = User::factory()->create();
        Order::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/ordenes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'status',
                        'total_price',
                        'items',
                    ]
                ]
            ]);
    }

    /**
     * Test authenticated user can get single order
     */
    public function test_authenticated_user_can_get_single_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/ordenes/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'user_id',
                    'status',
                    'total_price',
                ]
            ]);
    }

    /**
     * Test user cannot view other user's orders
     */
    public function test_user_cannot_view_other_users_orders()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2, 'sanctum')
            ->getJson("/api/ordenes/{$order->id}");

        $response->assertStatus(403);
    }

    /**
     * Test authenticated user can create order
     */
    public function test_authenticated_user_can_create_order()
    {
        $user = User::factory()->create();
        $products = Product::factory(2)->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ordenes', [
                'items' => [
                    [
                        'id' => $products[0]->id,
                        'titulo' => $products[0]->titulo,
                        'cantidad' => 2,
                        'price' => $products[0]->price,
                    ],
                    [
                        'id' => $products[1]->id,
                        'titulo' => $products[1]->titulo,
                        'cantidad' => 1,
                        'price' => $products[1]->price,
                    ]
                ],
                'total_price' => 99.99,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'status',
                    'total_price',
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test create order with missing items
     */
    public function test_create_order_with_missing_items()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ordenes', [
                'total_price' => 99.99,
                // missing items
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }

    /**
     * Test unauthenticated user cannot create order
     */
    public function test_unauthenticated_user_cannot_create_order()
    {
        $response = $this->postJson('/api/ordenes', [
            'items' => [],
            'total_price' => 99.99,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test admin can approve order
     */
    public function test_admin_can_approve_order()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/ordenes/{$order->id}/status", ['status' => 'approved']);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'approved',
        ]);
    }

    /**
     * Test non-admin cannot approve order
     */
    public function test_non_admin_cannot_approve_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/ordenes/{$order->id}/status", ['status' => 'approved']);

        $response->assertStatus(403);
    }

    /**
     * Test user can cancel their own order
     */
    public function test_user_can_cancel_own_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/ordenes/{$order->id}/cancel");

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    /**
     * Test user cannot cancel other user's order
     */
    public function test_user_cannot_cancel_other_users_order()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user1->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user2, 'sanctum')
            ->putJson("/api/ordenes/{$order->id}/cancel");

        $response->assertStatus(403);
    }

    /**
     * Test cannot cancel already approved order
     */
    public function test_cannot_cancel_approved_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'approved'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/ordenes/{$order->id}/cancel");

        $response->assertStatus(400);
    }
}

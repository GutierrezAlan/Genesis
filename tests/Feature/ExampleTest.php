<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test complete user registration and login flow
     */
    public function test_user_registration_and_login_flow()
    {
        // Register new user
        $registerResponse = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $registerResponse->assertStatus(201);

        // Login with the new user
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['token']
            ]);

        $token = $loginResponse->json('data.token');
        
        // Get user profile
        $profileResponse = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/me');

        $profileResponse->assertStatus(200)
            ->assertJson([
                'data' => ['user' => [
                    'email' => 'john@example.com',
                ]]
            ]);
    }

    /**
     * Test complete order flow
     */
    public function test_complete_order_flow()
    {
        // Create user
        $user = User::factory()->create();
        
        // Create products
        $products = Product::factory(2)->create();

        // Create order as user
        $orderResponse = $this->actingAs($user, 'sanctum')
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

        $orderResponse->assertStatus(201);
        $orderId = $orderResponse->json('data.id');

        // Get order details
        $getOrderResponse = $this->actingAs($user, 'sanctum')
            ->getJson("/api/ordenes/{$orderId}");

        $getOrderResponse->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $orderId,
                    'user_id' => $user->id,
                    'status' => 'pending',
                ]
            ]);

        // Admin approves order
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $approveResponse = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/ordenes/{$orderId}/status", ['status' => 'approved']);

        $approveResponse->assertStatus(200);

        // Verify order is approved
        $verifyResponse = $this->actingAs($user, 'sanctum')
            ->getJson("/api/ordenes/{$orderId}");

        $verifyResponse->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => 'approved',
                ]
            ]);
    }

    /**
     * Test complete product management flow
     */
    public function test_complete_product_management_flow()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create product
        $createResponse = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/productos', [
                'titulo' => 'Test Product',
                'description' => 'Test Description',
                'category' => 'Electronics',
                'price' => 99.99,
                'stock' => 50,
            ]);

        $createResponse->assertStatus(201);
        $productId = $createResponse->json('data.id');

        // Get product
        $getResponse = $this->getJson("/api/productos/{$productId}");
        $getResponse->assertStatus(200);

        // Update product
        $updateResponse = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/productos/{$productId}", [
                'titulo' => 'Updated Product',
                'price' => 149.99,
                'stock' => 30,
            ]);

        $updateResponse->assertStatus(200);

        // Verify update
        $verifyResponse = $this->getJson("/api/productos/{$productId}");
        $verifyResponse->assertStatus(200)
            ->assertJson([
                'data' => [
                    'titulo' => 'Updated Product',
                    'price' => 149.99,
                ]
            ]);

        // Delete product
        $deleteResponse = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/productos/{$productId}");

        $deleteResponse->assertStatus(200);

        // Verify deletion
        $checkResponse = $this->getJson("/api/productos/{$productId}");
        $checkResponse->assertStatus(404);
    }

    /**
     * Test search and filter flow
     */
    public function test_search_and_filter_flow()
    {
        // Create test products
        Product::factory(3)->create([
            'category' => 'Electronics',
            'price' => 100,
        ]);
        
        Product::factory(2)->create([
            'category' => 'Clothing',
            'price' => 50,
        ]);

        // Get all products
        $allResponse = $this->getJson('/api/productos');
        $allResponse->assertStatus(200);
        $allCount = count($allResponse->json('data'));
        $this->assertEquals(5, $allCount);

        // Search by category
        $categoryResponse = $this->postJson('/api/buscar', [
            'category' => 'Electronics',
        ]);

        $categoryResponse->assertStatus(200);
        $electronicsCount = count($categoryResponse->json('data'));
        $this->assertEquals(3, $electronicsCount);

        // Search by price range
        $priceResponse = $this->postJson('/api/buscar', [
            'min_price' => 30,
            'max_price' => 80,
        ]);

        $priceResponse->assertStatus(200);
        $filteredCount = count($priceResponse->json('data'));
        $this->assertEquals(2, $filteredCount);

        // Get categories
        $categoriesResponse = $this->getJson('/api/categorias');
        $categoriesResponse->assertStatus(200);
    }

    /**
     * Test user permissions and authorization
     */
    public function test_user_permissions_and_authorization()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $normalUser = User::factory()->create();
        $normalUser->assignRole('user');

        $product = Product::factory()->create();

        // Normal user cannot create product
        $createAttempt = $this->actingAs($normalUser, 'sanctum')
            ->postJson('/api/productos', [
                'titulo' => 'New Product',
                'description' => 'Test',
                'category' => 'Test',
                'price' => 50,
            ]);

        $createAttempt->assertStatus(403);

        // Admin can create product
        $adminCreate = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/productos', [
                'titulo' => 'New Product',
                'description' => 'Test',
                'category' => 'Test',
                'price' => 50,
            ]);

        $adminCreate->assertStatus(201);

        // Normal user cannot delete product
        $deleteAttempt = $this->actingAs($normalUser, 'sanctum')
            ->deleteJson("/api/productos/{$product->id}");

        $deleteAttempt->assertStatus(403);

        // Admin can delete product
        $newProductId = $adminCreate->json('data.id');
        $adminDelete = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/productos/{$newProductId}");

        $adminDelete->assertStatus(200);
    }
}

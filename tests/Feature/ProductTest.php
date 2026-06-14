<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get all products
     */
    public function test_can_get_all_products()
    {
        Product::factory(5)->create();

        $response = $this->getJson('/api/productos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'titulo',
                        'description',
                        'price',
                        'stock',
                    ]
                ]
            ]);
    }

    /**
     * Test get single product
     */
    public function test_can_get_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/productos/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'titulo',
                    'description',
                    'price',
                    'stock',
                ]
            ]);
    }

    /**
     * Test get non-existent product returns 404
     */
    public function test_get_non_existent_product_returns_404()
    {
        $response = $this->getJson('/api/productos/99999');

        $response->assertStatus(404);
    }

    /**
     * Test admin can create product
     */
    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/productos', [
                'titulo' => 'New Product',
                'description' => 'A great product',
                'category' => 'Electronics',
                'price' => 99.99,
                'stock' => 50,
                'image' => 'product.jpg',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'titulo',
                    'description',
                    'price',
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'titulo' => 'New Product',
        ]);
    }

    /**
     * Test non-admin cannot create product
     */
    public function test_non_admin_cannot_create_product()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/productos', [
                'titulo' => 'New Product',
                'description' => 'A great product',
                'category' => 'Electronics',
                'price' => 99.99,
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test create product with missing required fields
     */
    public function test_create_product_with_missing_fields()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/productos', [
                'titulo' => 'New Product',
                // missing description, category, price
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['description', 'category', 'price']);
    }

    /**
     * Test create product with invalid price
     */
    public function test_create_product_with_invalid_price()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/productos', [
                'titulo' => 'New Product',
                'description' => 'A great product',
                'category' => 'Electronics',
                'price' => -10, // Invalid negative price
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test admin can update product
     */
    public function test_admin_can_update_product()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $product = Product::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/productos/{$product->id}", [
                'titulo' => 'Updated Product',
                'description' => 'Updated description',
                'category' => 'Updated Category',
                'price' => 149.99,
                'stock' => 100,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'titulo' => 'Updated Product',
            'price' => 149.99,
        ]);
    }

    /**
     * Test non-admin cannot update product
     */
    public function test_non_admin_cannot_update_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/productos/{$product->id}", [
                'titulo' => 'Updated Product',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test admin can delete product
     */
    public function test_admin_can_delete_product()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $product = Product::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/productos/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /**
     * Test non-admin cannot delete product
     */
    public function test_non_admin_cannot_delete_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/productos/{$product->id}");

        $response->assertStatus(403);
    }

    /**
     * Test unauthenticated user cannot create product
     */
    public function test_unauthenticated_user_cannot_create_product()
    {
        $response = $this->postJson('/api/productos', [
            'titulo' => 'New Product',
            'description' => 'A great product',
            'category' => 'Electronics',
            'price' => 99.99,
        ]);

        $response->assertStatus(401);
    }
}

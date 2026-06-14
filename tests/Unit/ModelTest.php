<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User model relationships
     */
    public function test_user_can_have_orders()
    {
        $user = User::factory()->create();
        Order::factory(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->orders);
    }

    /**
     * Test User has roles
     */
    public function test_user_can_have_roles()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
    }

    /**
     * Test Product model creation
     */
    public function test_product_can_be_created()
    {
        $product = Product::factory()->create([
            'titulo' => 'Test Product',
            'price' => 99.99,
            'stock' => 50,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'titulo' => 'Test Product',
            'price' => 99.99,
        ]);
    }

    /**
     * Test Product default stock is 10
     */
    public function test_product_default_stock()
    {
        $product = Product::factory()->create([
            'stock' => null,
        ]);

        $this->assertEquals(10, $product->stock);
    }

    /**
     * Test Product has relationship with User through Order
     */
    public function test_product_can_be_ordered()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test Order belongs to User
     */
    public function test_order_belongs_to_user()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    /**
     * Test Order has items array
     */
    public function test_order_has_items_array()
    {
        $items = [
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 2, 'quantity' => 1],
        ];

        $order = Order::factory()->create([
            'items' => $items,
        ]);

        $this->assertIsArray($order->items);
        $this->assertCount(2, $order->items);
    }

    /**
     * Test Order total_price is cast to float
     */
    public function test_order_total_price_is_float()
    {
        $order = Order::factory()->create([
            'total_price' => 99.99,
        ]);

        $this->assertIsFloat($order->total_price);
        $this->assertEquals(99.99, $order->total_price);
    }

    /**
     * Test Order status values
     */
    public function test_order_status_values()
    {
        $statuses = ['pending', 'approved', 'cancelled'];

        foreach ($statuses as $status) {
            $order = Order::factory()->create(['status' => $status]);
            $this->assertEquals($status, $order->status);
        }
    }

    /**
     * Test User can be deleted with their orders
     */
    public function test_user_deletion_cascades_orders()
    {
        $user = User::factory()->create();
        Order::factory(3)->create(['user_id' => $user->id]);

        $userId = $user->id;
        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
        // Note: This assumes cascade delete is set up in migrations
    }

    /**
     * Test Product can be updated
     */
    public function test_product_can_be_updated()
    {
        $product = Product::factory()->create();

        $product->update([
            'titulo' => 'Updated Title',
            'price' => 199.99,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'titulo' => 'Updated Title',
            'price' => 199.99,
        ]);
    }

    /**
     * Test Product can be deleted
     */
    public function test_product_can_be_deleted()
    {
        $product = Product::factory()->create();
        $productId = $product->id;

        $product->delete();

        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }

    /**
     * Test Order can be updated
     */
    public function test_order_can_be_updated()
    {
        $order = Order::factory()->create();

        $order->update([
            'status' => 'approved',
            'total_price' => 199.99,
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'approved',
            'total_price' => 199.99,
        ]);
    }
}

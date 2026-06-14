<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        Product::factory(3)->create([
            'category' => 'Electronics',
            'price' => 100,
        ]);
        
        Product::factory(2)->create([
            'category' => 'Clothing',
            'price' => 50,
        ]);
        
        Product::factory(3)->create([
            'category' => 'Books',
            'price' => 20,
        ]);
    }

    /**
     * Test get all categories
     */
    public function test_can_get_all_categories()
    {
        $response = $this->getJson('/api/categorias');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ]
                ]
            ]);
    }

    /**
     * Test search products by category
     */
    public function test_can_search_products_by_category()
    {
        $response = $this->postJson('/api/buscar', [
            'category' => 'Electronics',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'titulo',
                        'category',
                        'price',
                    ]
                ]
            ]);

        $products = $response->json('data');
        $this->assertCount(3, $products);
        
        foreach ($products as $product) {
            $this->assertEquals('Electronics', $product['category']);
        }
    }

    /**
     * Test search products by price range
     */
    public function test_can_search_products_by_price_range()
    {
        $response = $this->postJson('/api/buscar', [
            'min_price' => 30,
            'max_price' => 100,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'titulo',
                        'price',
                    ]
                ]
            ]);

        $products = $response->json('data');
        
        foreach ($products as $product) {
            $this->assertGreaterThanOrEqual(30, $product['price']);
            $this->assertLessThanOrEqual(100, $product['price']);
        }
    }

    /**
     * Test search products by title
     */
    public function test_can_search_products_by_title()
    {
        $product = Product::first();
        
        $response = $this->postJson('/api/buscar', [
            'titulo' => $product->titulo,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'titulo',
                    ]
                ]
            ]);
    }

    /**
     * Test search with multiple filters
     */
    public function test_can_search_with_multiple_filters()
    {
        $response = $this->postJson('/api/buscar', [
            'category' => 'Electronics',
            'min_price' => 50,
            'max_price' => 150,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'titulo',
                        'category',
                        'price',
                    ]
                ]
            ]);

        $products = $response->json('data');
        
        foreach ($products as $product) {
            $this->assertEquals('Electronics', $product['category']);
            $this->assertGreaterThanOrEqual(50, $product['price']);
            $this->assertLessThanOrEqual(150, $product['price']);
        }
    }

    /**
     * Test search with no matches
     */
    public function test_search_with_no_matches()
    {
        $response = $this->postJson('/api/buscar', [
            'category' => 'NonExistentCategory',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => []
            ]);
    }

    /**
     * Test search with invalid price range
     */
    public function test_search_with_invalid_price_range()
    {
        $response = $this->postJson('/api/buscar', [
            'min_price' => 'invalid',
            'max_price' => 100,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test search returns correct number of results
     */
    public function test_search_returns_correct_number_of_results()
    {
        $response = $this->postJson('/api/buscar', [
            'category' => 'Clothing',
        ]);

        $response->assertStatus(200);
        
        $products = $response->json('data');
        $this->assertCount(2, $products);
    }
}

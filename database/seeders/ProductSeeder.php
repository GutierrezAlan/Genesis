<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Leer los productos desde un archivo JSON
        // $json = file_get_contents(database_path('seeders/productos.json'));
        // $products = json_decode($json, true);

        // foreach ($products as $productData) {
        //     Product::create(
        //         [
        //             'id' => $productData['id'],
        //             'titulo' => $productData['titulo'],
        //             'description' => $productData['descripcion'],
        //             'category' => $productData['categoria'],
        //             'price' => $productData['precio'],
        //             'stock' => $productData['stock'] ?? 10,
        //         ]
        //     );
        // }

        // Ejecutar la factory para generar productos adicionales
        Product::factory(5)->create();
    }
}

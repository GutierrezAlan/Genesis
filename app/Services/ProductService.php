<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * Obtener todos los productos.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Product::with('images')->get();
    }

    /**
     * Crear un producto.
     *
     * @param  array  $data
     * @return Product
     */
    public function create(array $data, ?UploadedFile $imageFile = null): Product
    {
        if (!isset($data['stock'])) {
            $data['stock'] = 10;
        }
        // 🔥 asegurarse que NO exista image
        unset($data['image']);

        $imageFile = request()->file('image'); // 👈 tomar desde request
        Log::info($data);
        $product = Product::create($data);

        if ($imageFile) {
   
         $this->storeImage($product, $imageFile);;
        }

        return $product->load('images');
    }

    /**
     * Retornar un producto.
     *
     * @param  Product  $product
     * @return Product
     */
    public function find(Product $product): Product
    {
        return $product->load('images');
    }

    /**
     * Actualizar un producto.
     *
     * @param  Product  $product
     * @param  array  $data
     * @return Product
     */
    public function update(Product $product, array $data, ?UploadedFile $imageFile = null): Product
    {
        // Quitar image del array para evitar errores en update()
        unset($data['image']);

        Log::info('Updating product', ['id' => $product->id, 'data' => $data, 'has_image' => $imageFile instanceof UploadedFile]);

        // Actualizar datos del producto
        $product->update($data);

        // Si viene nueva imagen
        if ($imageFile instanceof UploadedFile) {

            // eliminar imágenes anteriores
            $product->images()->delete();

            // guardar nueva imagen
            $this->storeImage($product, $imageFile);
        }

    
        return $product->load('images');
    }

    /**
     * Eliminar un producto.
     *
     * @param  Product  $product
     * @return bool|null
     */
    public function delete(Product $product): bool|null
    {
        $product->images()->delete();
        return $product->delete();
    }

    /**
     * Buscar productos por título o descripción.
     *
     * @param  string  $query
     * @param  int  $limit
     * @return Collection
     */
    public function search(string $query, int $limit = 10): Collection
    {
        return Product::with('images')
            ->where('titulo', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    protected function storeImage(Product $product, UploadedFile $image): Image
    {
        $path = $image->store('products', 'public');
        Log::info('GUARDANDO IMAGEN');

        $imageModel = $product->images()->create([

            'filename' => $image->hashName(),
            'path' => $path,
            'mime_type' => $image->getClientMimeType(),
            'size' => $image->getSize(),
        ]);
        Log::info($imageModel);
        return $imageModel;
    }
}

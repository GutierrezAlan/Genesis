<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of products
     */
    public function index()
    {
        try {
            $products = $this->service->getAll();

            return response()->json([
                'status' => 'success',
                'message' => 'Productos obtenidos exitosamente',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new product (Admin only)
     */
    public function store(Request $request)
    {
        try {
          
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'stock' => 'sometimes|integer|min:0',
                'image' => 'nullable|image|max:5120',
            ]);

            // Handle image file separately
            $imageFile = $request->file('image');
           

            $product = $this->service->create($validated, $imageFile);

            return response()->json([
                'status' => 'success',
                'message' => 'Producto creado exitosamente',
                'data' => $product
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Errores de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product
     */
    public function show(Product $producto)
    {
        try{
        //    dd(Product::find($id));

            $product = $this->service->find($producto);

            return response()->json([
                'status' => 'success',
                'message' => 'Producto obtenido exitosamente',
                'data' => $producto
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a product (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            Log::info($request->all());
            Log::info($request->file('image'));
            $validated = $request->validate([
                'titulo' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'price' => 'nullable|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
                'image' => 'nullable|image|max:5120',
            ]);


            // Handle image file separately
            $imageFile = $request->file('image');
         

            $product = $this->service->update($product, $validated, $imageFile);



            return response()->json([
                'status' => 'success',
                'message' => 'Producto actualizado exitosamente',
                'data' => $product
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Errores de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product (Admin only)
     */
    public function destroy(Product $producto)
    {
        try {
            $this->service->delete($producto);

            return response()->json([
                'status' => 'success',
                'message' => 'Producto eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Search products by title or description
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q');

            if (empty($query)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El parámetro de búsqueda es requerido'
                ], 400);
            }

            $productos = $this->service->search($query);

            return response()->json([
                'status' => 'success',
                'message' => 'Búsqueda realizada exitosamente',
                'data' => $productos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al realizar la búsqueda',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

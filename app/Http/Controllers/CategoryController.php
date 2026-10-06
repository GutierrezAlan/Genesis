<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class CategoryController extends Controller
{
    /**
     * Filtrar productos por categoría
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $filters = $request->validate([
            'category' => 'sometimes|string|max:100',
            'titulo' => 'sometimes|string|max:255',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0|gte:min_price',
        ]);

        try {
            if ($filters === []) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Debes indicar al menos un filtro',
                ], 400);
            }

            $query = Product::query();

            if (isset($filters['category'])) {
                $query->where('category', $filters['category']);
            }
            if (isset($filters['titulo'])) {
                $query->where('titulo', 'like', '%'.$filters['titulo'].'%');
            }
            if (isset($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            }
            if (isset($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }

            $productos = $query->get();

            // Si no encuentra, retornar array vacío
            if ($productos->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No se encontraron productos en esta categoría',
                    'data' => [],
                    'total' => 0
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Productos obtenidos exitosamente',
                'data' => $productos,
                'total' => $productos->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al filtrar productos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las categorías disponibles
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerCategorias()
    {
        try {
            // Obtener categorías únicas de la tabla products (campo text)
            $categorias = Product::distinct()
                ->pluck('category')
                ->filter()
                ->values();

            return response()->json([
                'status' => 'success',
                'message' => 'Categorías obtenidas exitosamente',
                'data' => $categorias
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener categorías: ' . $e->getMessage()
            ], 500);
        }
    }
}

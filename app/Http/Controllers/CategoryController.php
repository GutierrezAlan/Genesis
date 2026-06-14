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
        try {
            $category = $request->input('category');
            
            // Validación: la categoría es requerida
            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'La categoría es requerida'
                ], 400);
            }

            // Buscar productos por el nombre de categoría (campo text original)
            // o por la relación con Category si usa category_id
            $productos = Product::where('category',$category)
                ->get();

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

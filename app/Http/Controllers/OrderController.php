<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display all orders for admin or user's orders
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->hasRole('admin')) {
                // Admin ve todos los pedidos
                $orders = Order::with('user')->latest()->get();
            } else {
                // Usuario solo ve sus pedidos
                $orders = Order::where('user_id', $user->id)->latest()->get();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pedidos obtenidos exitosamente',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener pedidos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new order (Usuario loggeado)
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'required|integer',
                'items.*.titulo' => 'required|string',
                'items.*.price' => 'required|numeric',
                'items.*.cantidad' => 'required|integer|min:1',
                'total_price' => 'required|numeric|min:0',
            ]);

            $order = Order::create([
                'user_id' => $user->id,
                'items' => $validated['items'],
                'total_price' => $validated['total_price'],
                'status' => 'pending', // Estados: pending, approved, cancelled
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pedido creado exitosamente',
                'data' => $order
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
                'message' => 'Error al crear pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a specific order
     */
    public function show(Order $order, Request $request)
    {
        try {
            $user = $request->user();

            // Autorización: solo admin o el dueño del pedido
            if (!$user->hasRole('admin') && $order->user_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No tienes permiso para ver este pedido'
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pedido obtenido exitosamente',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   /**
     * Delete an order (Admin only)
     */
    public function delete(Order $order)
    {
       $order->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Orden eliminada',
            'data' => $order
        ]);
    }

    /**
     * Approve an order (Admin only)
     */


    public function updateOrderStatus(Order $order, Request $request)
    {
    // 1. Validar que el status sea uno permitido
    $request->validate([
        'status' => 'required|in:completed,cancelled,deleted'
    ]);

    $newStatus = $request->status;

    // 2. Lógica adicional según el nuevo estado (opcional)
    try {
        switch ($newStatus) {
            case 'completed':
                // Podrías agregar lógica de aprobación (stock, validaciones)
                $order->update(['status' => 'completed']);
                $message = 'Pedido aprobado exitosamente';
                break;
            case 'cancelled':
                $order->update(['status' => 'cancelled']);
                $message = 'Pedido cancelado';
                // Aquí podrías liberar stock, etc.
                break;
            case 'pending':
                $order->update(['status' => 'pending']);
                $message = 'Pedido pendiente';
                // Aquí podrías liberar stock, etc.
                break;
            case 'deleted':
                // Si quieres borrado lógico (mantener en BD pero no mostrarlo)
                // $order->update(['status' => 'deleted']);
                // O si prefieres borrado físico:
                $order->delete();
                $message = 'Pedido eliminado';
                break;
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $order
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al actualizar el pedido',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Cancel an order (Admin or owner)
     */
    public function cancel(Order $order, Request $request)
    {
        try {
            $user = $request->user();

            // Autorización: admin o dueño del pedido
            if (!$user->hasRole('admin') && $order->user_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No tienes permiso para cancelar este pedido'
                ], 403);
            }

            $order->update(['status' => 'cancelled']);

            return response()->json([
                'status' => 'success',
                'message' => 'Pedido cancelado exitosamente',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cancelar pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

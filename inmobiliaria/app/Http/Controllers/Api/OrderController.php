<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder()
    {
        try {
            $user = Auth::user();
            $cartItems = $user->cartItems()->with('property')->get();
    
            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'El carrito está vacío.'], 400);
            }
    
            $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->property->price);
    
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);
    
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'property_id' => $cartItem->property_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->property->price,
                ]);
            }
    
            // Vaciar carrito
            $user->cartItems()->delete();
    
            // Recargar el pedido con sus items y propiedades
            $order->load('orderItems.property');
    
            return response()->json([
                'message' => 'Pedido realizado exitosamente.',
                'order' => $order,
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar el pedido.',
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTrace() : [],
            ], 500);
        }
    }
    

    public function getOrderHistory()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.property')->get();

        return response()->json($orders);
    }
}

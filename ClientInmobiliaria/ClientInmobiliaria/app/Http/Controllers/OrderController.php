<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('property')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $cartItems->sum(function ($item) {
                return $item->quantity * $item->property->price;
            }),
            'status' => 'pending'
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'property_id' => $cartItem->property_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->property->price,
            ]);
        }

        // Vaciar el carrito después de crear el pedido
        $user->cartItems()->delete();

        return response()->json($order, 201);
    }

    public function getOrderHistory()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.property')->get();

        return response()->json($orders);
    }
}

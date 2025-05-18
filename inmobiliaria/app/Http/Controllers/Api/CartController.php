<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  // Importa DB para usar raw

class CartController extends Controller
{
    public function addToCart($propertyId)
    {
        try {
            $user = Auth::user();
            $property = Property::findOrFail($propertyId);
    
            if ($property->user_id === $user->id) {
                return response()->json(['error' => 'No puedes añadir tu propia propiedad al carrito'], 400);
            }
    
            if ($property->status !== 'available') {
                return response()->json(['error' => 'La propiedad no está disponible'], 400);
            }
    
            // Solo una por propiedad, si ya está en el carrito no añadimos otra
            $exists = CartItem::where('user_id', $user->id)
                              ->where('property_id', $propertyId)
                              ->exists();
    
            if ($exists) {
                return response()->json(['message' => 'La propiedad ya está en el carrito'], 200);
            }
    
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'property_id' => $propertyId,
            ]);
    
            return response()->json([
                'message' => 'Propiedad añadida al carrito correctamente',
                'cart_item' => $cartItem
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al añadir al carrito',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    


public function getCart()
{
    try {
        $user = Auth::user();

        $cartItems = $user->cartItems()->with('property')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'El carrito está vacío.'
            ], 200);
        }

        return response()->json($cartItems, 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al obtener el carrito',
            'message' => $e->getMessage(),
        ], 500);
    }
}


public function removeFromCart($id)
{
    try {
        $user = Auth::user();
        $cartItem = CartItem::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'message' => 'El ítem del carrito se ha eliminado correctamente'
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Elemento del carrito no encontrado',
            'message' => $e->getMessage(),
        ], 404);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al eliminar el ítem del carrito',
            'message' => $e->getMessage(),
        ], 500);
    }
}


}

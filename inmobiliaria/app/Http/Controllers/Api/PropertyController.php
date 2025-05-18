<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::all();
        return response()->json($properties, 200);
    }

    public function show($id)
    {
        $property = Property::with('user')->find($id);
        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }
        return response()->json($property, 200);
    }
    

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'type' => 'required',
                'location' => 'required',
                'status' => 'required',
            ]);
    
            $userId = auth()->id();
    
            if (!$userId) {
                return response()->json(['error' => 'Unauthorized - user not found'], 401);
            }
    
            $property = Property::create([
                ...$validated,
                'user_id' => $userId,
                'buyer_id' => null,
            ]);
    
            return response()->json(['message' => 'Property created successfully', 'property' => $property], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ], 500);
        }
    }
    

    public function update(Request $request, $id)
{
    try {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        // Opcional: si usas políticas de autorización
        // $this->authorize('update', $property);

        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'type' => 'required|in:sale,rent',
            'location' => 'required',
            'status' => 'required|in:available,sold,rented',
        ]);

        $property->update($validated);

        return response()->json(['message' => 'Property updated successfully', 'property' => $property], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(), // Puedes quitar esto en producción
        ], 500);
    }
}

    

public function destroy($id)
{
    try {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        if ($property->user_id !== auth()->id()) {
            return response()->json(['error' => 'Forbidden – You do not own this property'], 403);
        }

        $property->delete();

        return response()->json(['message' => 'Property deleted successfully'], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage(),
        ], 500);
    }
}



public function buy($id)
{
    try {
        $property = Property::find($id);
        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($property->user_id == auth()->id()) {
            return response()->json(['error' => 'Cannot buy your own property'], 403);
        }

        if ($property->status !== 'available') {
            return response()->json(['error' => 'Property is not available'], 400);
        }

        $property->status = 'sold'; // O 'rented' si es alquiler
        $property->buyer_id = auth()->id();
        $property->save();

        return response()->json(['message' => 'Property purchased successfully', 'property' => $property], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ], 500);
    }
}



public function purchase(Request $request, $id)
{
    try {
        $property = Property::find($id);
        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        if ($property->user_id == $user->id) {
            return response()->json(['error' => 'Cannot buy your own property'], 403);
        }

        if ($property->status !== 'available') {
            return response()->json(['error' => 'Property is not available'], 400);
        }

        // Cambiar estado de propiedad a vendida y asignar comprador
        $property->status = 'sold'; // O 'rented' si es alquiler
        $property->buyer_id = $user->id;
        $property->save();

        // **Eliminar propiedad del carrito**
        Cart::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->delete();

        return response()->json(['message' => 'Property purchased successfully', 'property' => $property], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ], 500);
    }
}





}
